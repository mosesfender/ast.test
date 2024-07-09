<?php
/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

namespace app\components;

use yii\bootstrap5\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\web\View;

class SVSGridView extends GridView
{
    public array $pageSizeValues = [10, 20, 50];
    public string $pageSizeClass = 'form-select';
    public array $pageSizeOptions = [];
    public string $pageSizeLabel = '';
    public string $pageSizeTitle = 'Rows per page';
    public string $sorterLabel = 'Sort';
    public string $sorterTitle = '';
    
    public function renderSection($name)
    {
        return match ($name) {
            '{summary}'  => $this->renderSummary(),
            '{items}'    => $this->renderItems(),
            '{pager}'    => $this->renderPager(),
            '{sorter}'   => $this->renderSorter(),
            '{pagesize}' => $this->renderPagesize(),
            default      => false,
        };
    }
    
    public function renderSorter(): string
    {
        $sorter = parent::renderSorter();
        return $this->sorterLabel ? \yii::t('app', $this->sorterLabel) . $sorter : $sorter;
    }
    
    public function renderPagesize(): string
    {
        $this->pageSizeOptions = ArrayHelper::merge($this->pageSizeOptions,
            ($this->pageSizeTitle ? ['title' => \yii::t('app', $this->pageSizeTitle), 'data-toggle' => 'tooltip'] : [])
        );
        $result = Html::beginTag('select', ArrayHelper::merge(
            [
                'id'                  => sprintf('%s_%s', $this->getId(), 'pagesize'),
                'class'               => $this->pageSizeClass,
                'data-param-pagesize' => $this->dataProvider->getPagination()->pageSizeParam,
            ],
            $this->pageSizeOptions
        ));
        foreach ($this->pageSizeValues as $value) {
            $result .= Html::tag('option', $value, ArrayHelper::merge(['value' => $value],
                ($this->dataProvider->getPagination()->pageSize == $value ? ['selected' => 'selected'] : [])
            ));
        }
        $result .= Html::endTag('select');
        $this->registerPagesizeHandler();
        return $result;
    }
    
    protected function registerPagesizeHandler()
    {
        $js = <<<JS
            document.querySelector('#{$this->getId()}_pagesize').addEventListener('change', function(ev){
                var _j = $(this).closest('[data-pjax-container]'),
                    _u = new URL(location.href),
                    _p = this.getAttribute('data-param-pagesize'),
                    _v = this.value;
                _u.searchParams.set(_p,_v);
                cookie.write('ast_pagesize', _v);
                if(_j.length) {
                    $.pjax({
                        container: '#' + _j.get(0).id,
                        url: _u.toString()
                    });
                } else {
                    location.assign(_u.toString());
                }
            });
        JS;
        $this->getView()->registerJs(JsonHelper::JSCompress($js), View::POS_READY, $this->getId() . '_pagesize');
    }
}
