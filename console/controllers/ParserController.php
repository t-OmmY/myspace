<?php
namespace console\controllers;

use common\models\IncomeSource;
use common\models\OutgoSource;
use common\models\OutgoType;
use common\models\Wallet;
use yii\console\Controller;
use yii\helpers\Console;

class ParserController extends Controller
{
    const USER_ID = '1';

    private $wallet_map = [
        '$' => 'USD',
        'грн' => 'UAH'
    ];

    private $path;

    public function init()
    {
        $this->path = \Yii::getAlias('@runtime') . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR;
    }

    /**
     * Default action
     */
    public function actionIndex()
    {
        $files = $this->getAllFiles();
        if (!$files) {
            $this->stderr('no files found in directory' . PHP_EOL, Console::FG_RED);
            return;
        }

        asort($files);
        foreach ($files as $file) {
            $this->stdout($file . PHP_EOL);
            $action = pathinfo($file, PATHINFO_FILENAME);
            $this->$action($file);
        }
    }

    private function getAllFiles()
    {
        $file_list = [];
        $handle = opendir($this->path);
        if (!$handle) {
            return false;
        }

        while (false !== ($file = readdir($handle))) {
            $file_path = $this->path . $file;
            $ext = pathinfo($file_path, PATHINFO_EXTENSION);
            if (strtolower($ext) === 'csv') {
                $file_list[] = $file;
            }
        }

        closedir($handle);

        return $file_list;
    }

    /**
     * @param $file_name
     */
    public function income($file_name)
    {
        $query = 'Insert into income (date, income_source_id, value, wallet_id, description, user_id, created_at, updated_at) VALUE ';
        $file = fopen($this->path . $file_name, 'r');
        while (($line = fgetcsv($file, 1000, '	')) !== FALSE) {
            array_pop($line);
            $date = str_replace('/', '-', $line[0]);
            $line[0] = date('Y-m-d h:i:s', strtotime($date));

            $income_source = IncomeSource::find()->where(['name' => $line['1']])->one();
            if (empty($income_source)){
                $income_source = new IncomeSource();
                $income_source->name = $line[1];
                $income_source->user_id = self::USER_ID;
                $income_source->save();
            }
            $line[1] = $income_source->id;

            $line[2] = str_replace(',', '.', $line[2]);

            $wallet = Wallet::find()->where(['code' => $this->wallet_map[$line[3]]])->one();
            if (empty($wallet)){
                $wallet = new Wallet();
                $wallet->name = $line[3];
                $wallet->code = $line[3];
                $wallet->user_id = self::USER_ID;
                $wallet->save();
            }
            $line[3] = $wallet->id;

            $line[5] = self::USER_ID;
            $line[6] = strtotime($line[0]);
            $line[7] = strtotime($line[0]);

            $query .= "('" . implode("', '", $line) . "'), ";
        }
        $query = rtrim($query, ', ');
        fclose($file);
        $this->stdout($query . PHP_EOL);
        \Yii::$app->db->createCommand($query)->execute();
    }

    /**
     * @param $file_name
     */
    public function outgo($file_name)
    {
        $query = 'Insert into outgo (date, outgo_source_id, outgo_type_id, value, wallet_id, description, user_id, created_at, updated_at) VALUE ';
        $file = fopen($this->path . $file_name, 'r');
        while (($line = fgetcsv($file, 1000, '	')) !== FALSE) {
            array_pop($line);
            $date = str_replace('/', '-', $line[0]);
            $line[0] = date('Y-m-d h:i:s', strtotime($date));

            $outgo_source = OutgoSource::find()->where(['name' => $line['1']])->one();
            if (empty($outgo_source)){
                $outgo_source = new OutgoSource();
                $outgo_source->name = $line[1];
                $outgo_source->user_id = self::USER_ID;
                $outgo_source->save();
            }
            $line[1] = $outgo_source->id;

            $outgo_type = OutgoType::find()->where(['name' => $line['2']])->one();
            if (empty($outgo_type)){
                $outgo_type = new OutgoType();
                $outgo_type->name = $line[2];
                $outgo_type->user_id = self::USER_ID;
                $outgo_type->outgo_source_id = $outgo_source->id;
                $outgo_type->save();
            }
            $line[2] = $outgo_type->id;

            $line[3] = str_replace(',', '.', $line[3]);

            $wallet = Wallet::find()->where(['code' => $this->wallet_map[$line[4]]])->one();
            if (empty($wallet)){
                $wallet = new Wallet();
                $wallet->name = $line[4];
                $wallet->code = $line[4];
                $wallet->user_id = self::USER_ID;
                $wallet->save();
            }
            $line[4] = $wallet->id;

            $line[6] = self::USER_ID;
            $line[7] = strtotime($line[0]);
            $line[8] = strtotime($line[0]);

            $query .= "('" . implode("', '", $line) . "'), ";
        }
        $query = rtrim($query, ', ');
        fclose($file);
        $this->stdout($query . PHP_EOL);
        \Yii::$app->db->createCommand($query)->execute();
    }
}