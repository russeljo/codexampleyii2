<?php

namespace console\controllers;

use common\models\db\UserActives;
use common\models\mutators\UserActivesMutator;
use common\services\actives\AliasCreateService;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\BaseConsole;
use yii\helpers\Console;

/**
 * Заполнение у актива alias (символьный код)
 * Class AliasController
 * @package console\controllers
 * @author Ruslan Mukhamedyarov
 */
class AliasController extends Controller
{
    private array $models = [];
    private int   $countModels = 0;

    public function __construct($id, $module, $config = [])
    {
        $this->models = $this->getModels();
        $this->countModels = count($this->models);

        parent::__construct($id, $module, $config);
    }

    /**
     * Вывести количество активов без символьного кода. Запуск скрипта: yii alias/count
     * @return int
     * @author Ruslan Mukhamedyarov
     */
    public function actionCount(): int
    {
        $this->stdout('Ищем активы без символьного кода...', BaseConsole::FG_YELLOW);
        echo PHP_EOL;
        echo PHP_EOL;
        $this->stdout('Найдено: ' . $this->countModels, BaseConsole::FG_GREEN);
        echo PHP_EOL;
        return ExitCode::OK;
    }

    /**
     * Вывести названия активов без alias и вариант сгенерированного alias. Запуск скрипта: yii alias/list
     * @return int
     * @author Ruslan Mukhamedyarov
     */
    public function actionList()
    {
        if ($this->countModels > 0) {
            $this->stdout('Найдено ' . $this->countModels . 'шт. ', BaseConsole::FG_GREEN);
        } else {
            $this->stdout('Ничего не найдено', BaseConsole::FG_YELLOW);

            return ExitCode::OK;
        }

        echo PHP_EOL;
        echo PHP_EOL;

        if (Console::confirm("Хотите вывести список?")) {

            foreach ($this->models as $model) {
                $alias = AliasCreateService::create($model->title);
                $this->stdout($model->title, BaseConsole::FG_GREEN);
                echo PHP_EOL;
                $this->stdout($alias, BaseConsole::FG_YELLOW);
                echo PHP_EOL;
                echo PHP_EOL;
            }

        } else {
            $this->stdout('Отменено', BaseConsole::FG_YELLOW);

            return ExitCode::OK;
        }

        echo PHP_EOL;

        try {

            $this->stdout('---___ END ___---', BaseConsole::FG_GREEN);
            return ExitCode::OK;

        } catch (\Exception $e) {

            echo $e->getMessage();
            $this->stdout($e->getMessage(), BaseConsole::FG_RED);
            return ExitCode::UNSPECIFIED_ERROR;

        }
	}

    /**
     * Заполнить alias активов. Запуск скрипта: yii alias/fill
     * @return int
     * @author Ruslan Mukhamedyarov
     */
    public function actionFill()
    {
        if ($this->countModels > 0) {
            $this->stdout('Найдено ' . $this->countModels . 'шт. ...', BaseConsole::FG_GREEN);
        } else {
            $this->stdout('Ничего не найдено', BaseConsole::FG_YELLOW);

            return ExitCode::OK;
        }

        echo PHP_EOL;

        $resCount = 0;
        $errCount = 0;

        if (Console::confirm("Хотите заполнить символьные коды?")) {

            foreach ($this->models as $model) {
                $this->stdout($model->title, BaseConsole::FG_GREEN);
                echo PHP_EOL;

                if ($model->save()) {
                    $this->stdout('done... ' . $model->alias, BaseConsole::FG_YELLOW);
                    $resCount++;
                } else {
                    $this->stdout('ОШИБКА...', BaseConsole::FG_RED);
                    $errCount++;
                }

                echo PHP_EOL;
                echo PHP_EOL;
            }

        } else {
            $this->stdout('Отменено', BaseConsole::FG_YELLOW);

            return ExitCode::OK;
        }

        try {

            $this->stdout('Символьные коды добавлены для ' . $resCount . 'шт. из ' . $this->countModels, BaseConsole::FG_GREEN);
            echo PHP_EOL;
            $errCount > 0 ? $this->stdout('С ошибками ' . $errCount . 'шт. из ' . $this->countModels, BaseConsole::FG_RED) : null;
            return ExitCode::OK;

        } catch (\Exception $e) {

            echo $e->getMessage();
            $this->stdout($e->getMessage(), BaseConsole::FG_RED);
            return ExitCode::UNSPECIFIED_ERROR;

        }
	}


    private function getModels(): array
    {
        return UserActives::find()
            ->andWhere([UserActives::ATTR_ALIAS => [null, '']])
            ->all();
    }



}