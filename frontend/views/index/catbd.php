<?php
use yii\helpers\Url;
$nodes=frontend\models\GoodCates::generateNodes();
?>
<div class="cat_bd">
<?php foreach ($nodes as $index=>$node):?>
    <div class="cat <?=($index%2)?'item1':''?>">
        <h3><a href="<?=Url::to(['index/list','cates'=>$node['id']])?>"><?=$node['name']?></a> <b></b></h3>
        <div class="cat_detail">
            <?php foreach ($node['children'] as $children):?>
                <dl class="dl_1st">
                    <dt><a href="<?=Url::to(['index/list','cates'=>$children['id']])?>"><?=$children['name']?></a></dt>
                    <?php foreach ($children['children'] as $child):?>
                        <dd>
                            <a href="<?=Url::to(['index/list','cates'=>$child['id']])?>"><?=$child['name']?></a>
                        </dd>
                    <?php endforeach;?>
                </dl>
            <?php endforeach;?>
        </div>
    </div>
<?php endforeach;?>
</div>