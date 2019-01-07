<?php

class Chart
{
    public $name;
    public $color;
    public $data;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Chart
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param mixed $color
     * @return Chart
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     * @return Chart
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }
}

class ChartBuilder
{
    const TEMPLATE = "
\n<div id='%s' class='chart'></div>
<script>
    Highcharts.chart('%s', %s);
</script>\n";

    /** @var Chart[] */
    private $id;
    private $charts;
    private $title;
    private $subtitle;
    private $xTitle;
    private $yTitle;

    public function __construct()
    {
        $this->id     = uniqid('id-', true);
        $this->charts = [];
    }

    public function addChart(Chart $chart): void
    {
        $this->charts[] = $chart;
    }

    /**
     * @param mixed $title
     * @return ChartBuilder
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param mixed $subtitle
     * @return ChartBuilder
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     * @param mixed $xTitle
     * @return ChartBuilder
     */
    public function setXTitle($xTitle)
    {
        $this->xTitle = $xTitle;

        return $this;
    }

    /**
     * @param mixed $yTitle
     * @return ChartBuilder
     */
    public function setYTitle($yTitle)
    {
        $this->yTitle = $yTitle;

        return $this;
    }

    public function build(): string
    {
        $chart = [
            'chart'       => ['type' => 'line'],
            'title'       => ['text' => $this->title],
            'subtitle'    => ['text' => $this->subtitle],
            'yAxis'       => ['title' => ['text' => $this->yTitle]],
            'xAxis'       => ['title' => ['text' => $this->xTitle], 'labels' => ['enabled' => false]],
            'plotOptions' => ['line' => [
                'dataLabels'          => ['enabled' => false],
                'marker'              => ['enabled' => false],
                'enableMouseTracking' => true
            ]],
            'series'      => []
        ];

        foreach ($this->charts as $ch) {
            $chart['series'][] = [
                'name'  => $ch->getName(),
                'color' => $ch->getColor(),
                'data'  => $ch->getData(),
            ];
        }

        return sprintf(self::TEMPLATE, $this->id, $this->id, json_encode($chart));
    }
}

function parseMonitoringFile(string $filePath)
{
    $fd   = fopen($filePath, 'r');
    $data = [];
    while (!feof($fd)) {
        $line = fgets($fd);

        if (empty($line)) {
            continue;
        }

        $log = parseMonitoringLine($line);

        if (empty($log)) {
            continue;
        }

        $timestamp = $log['timestamp'];
        if (!array_key_exists($timestamp, $data)) {
            $data[$timestamp] = [];
        }
        foreach ($log['data'] as $key => $d) {
            $data[$timestamp][] = $d['metrics'];
        }
    }

    fclose($fd);

    return $data;
}

function parseMonitoringLine(string $line): array
{
    $parsed = json_decode($line, true);
    if (!is_array($parsed) || !isset($parsed[0])) {
        return [];
    }

    return $parsed[0];
}

function parsePhantomFile(string $filePath)
{
    $fd   = fopen($filePath, 'r');
    $data = [];
    while (!feof($fd)) {
        $line = fgets($fd);

        if (empty($line)) {
            continue;
        }

        $log = parsePhantomLine($line);

        if ($log['netCode'] !== 0 || $log['protoCode'] !== 200) {
            continue;
        }

        $requestTime = (int)$log['time'];
        if (!array_key_exists($requestTime, $data)) {
            $data[$requestTime] = [];
        }

        $data[$requestTime][] = $log['intervalReal'] / 1000;
    }

    fclose($fd);

    return $data;
}

function parsePhantomLine(string $line): array
{
    $parsed = [];

    $line    = preg_replace('/[\s\t]/', ' ', trim($line));
    $explode = explode(' ', $line);

    if (count($explode) !== 12) {
        return [];
    }

    $parsed['time']          = (float)$explode[0];
    $parsed['tag']           = $explode[1];
    $parsed['intervalReal']  = (int)$explode[2];
    $parsed['connectTime']   = (int)$explode[3];
    $parsed['sendTime']      = (int)$explode[4];
    $parsed['latency']       = (int)$explode[5];
    $parsed['receiveTime']   = (int)$explode[6];
    $parsed['intervalEvent'] = (int)$explode[7];
    $parsed['sizeOut']       = (int)$explode[8];
    $parsed['sizeIn']        = (int)$explode[9];
    $parsed['netCode']       = (int)$explode[10];
    $parsed['protoCode']     = (int)$explode[11];

    return $parsed;
}

function getColorByName(string $name)
{
    switch ($name) {
        case 'php-fpm':
            return 'blue';
        case 'php-ppm':
            return 'red';
        case 'nginx-unit':
            return 'green';
        case 'road-runner':
            return 'purple';
        case 'react-php':
            return 'orange';
    }

    return 'white';
}

function normalizeData(array &$data)
{
    array_shift($data);
    array_shift($data);
}

if (!isset($argv[1])) {
    exit(1);
}
$dir = $argv[1];
if (!is_dir($dir)) {
    exit(1);
}

$outFile = __DIR__ . '/tank.html';
if (isset($argv[2])) {
    $outFile = $argv[2];
}

echo 'Input dir: ' . $dir . PHP_EOL;
echo 'Output file: ' . $outFile . PHP_EOL;

$phantom    = [];
$monitoring = [];
foreach (scandir($dir) as $dirName) {

    $subDir = $dir . '/' . $dirName;
    if (!is_dir($subDir)) {
        continue;
    }

    foreach (scandir($subDir) as $fileName) {
        $filePath = $subDir . '/' . $fileName;

        if (!is_file($filePath)) {
            continue;
        }

        if (1 === preg_match('/^monitoring\.log$/', $fileName)) {
            echo 'Handling ' . $filePath . '...' . PHP_EOL;
            $monitoring[$dirName] = parseMonitoringFile($filePath);
            continue;
        }

        if (1 === preg_match('/^phout_.*\.log$/', $fileName)) {
            echo 'Handling ' . $filePath . '...' . PHP_EOL;
            $phantom[$dirName] = parsePhantomFile($filePath);
            continue;
        }
    }
}

file_put_contents($outFile, file_get_contents(__DIR__ . '/template.html'));

$responseChart = new ChartBuilder();
$responseChart->setTitle('Response time');
$responseChart->setXTitle('Time');
$responseChart->setYTitle('Response (ms)');
foreach ($phantom as $dirName => $data) {
    $chartData = [];
    foreach ($data as $timestamp => $requestDurations) {
        $chartData[] = (int)(array_sum($requestDurations) / count($requestDurations));
    }

    normalizeData($chartData);

    $responseChart->addChart(
        (new Chart())
            ->setName($dirName)
            ->setColor(getColorByName($dirName))
            ->setData($chartData)
    );
}
file_put_contents($outFile, $responseChart->build(), FILE_APPEND);

$cpuUserChart = new ChartBuilder();
$cpuUserChart->setTitle('CPU user usage');
$cpuUserChart->setXTitle('Time');
$cpuUserChart->setYTitle('CPU (%)');

$cpuSystemChart = new ChartBuilder();
$cpuSystemChart->setTitle('CPU system usage');
$cpuSystemChart->setXTitle('Time');
$cpuSystemChart->setYTitle('CPU (%)');

$memUsageChart = new ChartBuilder();
$memUsageChart->setTitle('Memory usage');
$memUsageChart->setXTitle('Time');
$memUsageChart->setYTitle('Memory (MB)');

foreach ($monitoring as $dirName => $data) {
    $cpuUser   = [];
    $cpuSystem = [];
    $memUsed   = [];
    foreach ($data as $timestamp => $d) {
        $cpuUserSum   = 0;
        $cpuSystemSum = 0;
        $memUsedSum   = 0;
        foreach ($d as $item) {
            $cpuUserSum   += $item['custom:cpu-cpu-total_usage_system'];
            $cpuSystemSum += $item['custom:cpu-cpu-total_usage_user'];
            $memUsedSum   += $item['Memory_used'] / 1000000;
        }

        $count       = count($d);
        $cpuUser[]   = $cpuUserSum / $count;
        $cpuSystem[] = $cpuSystemSum / $count;
        $memUsed[]   = $memUsedSum / $count;
    }

    normalizeData($cpuUser);
    $cpuUserChart->addChart(
        (new Chart())
            ->setName($dirName)
            ->setColor(getColorByName($dirName))
            ->setData($cpuUser)
    );

    normalizeData($cpuSystem);
    $cpuSystemChart->addChart(
        (new Chart())
            ->setName($dirName)
            ->setColor(getColorByName($dirName))
            ->setData($cpuSystem)
    );

    normalizeData($memUsed);
    $memUsageChart->addChart(
        (new Chart())
            ->setName($dirName)
            ->setColor(getColorByName($dirName))
            ->setData($memUsed)
    );
}

file_put_contents($outFile, $cpuUserChart->build(), FILE_APPEND);
file_put_contents($outFile, $cpuSystemChart->build(), FILE_APPEND);
file_put_contents($outFile, $memUsageChart->build(), FILE_APPEND);

echo 'Done!' . PHP_EOL;

