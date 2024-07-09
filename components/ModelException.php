<?php

namespace app\components;

use Yii;
use Throwable;
use yii\base\Exception;

/**
 * Class ModelException
 *
 * Своеобразный класс исключения, вызываем его для отображения списка ошибок валидации модели.
 * Имеет статичный конструктор, позволяющий настраивать класс через контейнер щависимостей.
 *
 * Таким образом его можно создавать традиционным способом
 *
 * @example
 *      throw new ModelException('Некое сообщение', <Nodel::errors>)
 *
 * и. если мы его настраиваем в конфигурации через контейнер зависимостей, то
 * @example
 *      throw ModelException::create('Некое сообщение', <Nodel::errors>)
 *
 * Для получения списка ошибок, обёрнутых в HTML-тэги достаточно запросить объект исключения в виде строки:
 * @example
 *         try {
 *              ............
 *         } catch (ModelException $ex){
 *              echo $ex->toString();
 *         }
 * @package app\components
 */
class ModelException extends Exception
{
    public string $wrap     = '<div>%s</div>';
    public string $itemWrap = '<span>%s</span>';
    public array  $errors   = [];
    
    static function create($message = "", $modelErrors = [], Throwable $previous = null): ModelException
    {
        return Yii::createObject(ModelException::class, [
            'message'     => $message,
            'modelErrors' => $modelErrors,
            'previous'    => $previous
        ]);
    }
    
    public function __construct($message = "", $modelErrors = [], Throwable $previous = null)
    {
        $this->errors = $modelErrors;
        parent::__construct($message, $code = 1001, $previous);
    }
    
    public function toString()
    {
        $errs = [];
        foreach ($this->errors as $field => $list) {
            foreach ($list as $err) {
                $errs[] = sprintf($this->itemWrap, $err);
            }
        }
        return sprintf($this->wrap, implode($errs));
    }
}
