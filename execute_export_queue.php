<?php




/** @var \Magento\Framework\App\Http $app *\/
 * $app = $bootstrap->createApplication(\Magento\Framework\App\Http::class);
 * $bootstrap->run($app);
 * --------------------------------------------
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

try {
    require __DIR__ . '/app/bootstrap.php';
} catch (\Exception $e) {
    echo <<<HTML
<div style="font:12px/1.35em arial, helvetica, sans-serif;">
    <div style="margin:0 0 25px 0; border-bottom:1px solid #ccc;">
        <h3 style="margin:0;font-size:1.7em;font-weight:normal;text-transform:none;text-align:left;color:#2f2f2f;">
        Autoload error</h3>
    </div>
    <p>{$e->getMessage()}</p>
</div>
HTML;
    exit(1);
}

$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);
/** @var \Magento\Framework\App\Http $app */
$app = $bootstrap->createApplication(\Magento\Framework\App\Http::class);

$ob = Magento\Framework\App\ObjectManager::getInstance();
/** @var \Magento\ImportExport\Model\Export\Consumer $export */
$export = $ob->create(\Magento\ImportExport\Model\Export\Consumer::class);

/** @var \Magento\MysqlMq\Model\Message $message */
$message = $ob->create(\Magento\MysqlMq\Model\Message::class);
$message->load(4);
$data = json_decode($message->getData('body'), 1);

$exportInfo = new \Magento\ImportExport\Model\Export\Entity\ExportInfo();
$exportInfo->setEntity($data['entity']);
$exportInfo->setFileName($data['file_name']);
$exportInfo->setFileFormat($data['file_format']);
$exportInfo->setContentType($data['content_type']);
$exportInfo->setSkipAttr($data['skip_attr']);
$exportInfo->setExportFilter($data['export_filter']);
$export->process($exportInfo);
echo "exporte {$data['file_name']} done.";
echo PHP_EOL;

// vendor/magento/module-catalog-import-export/Model/Export/Product.php:753
