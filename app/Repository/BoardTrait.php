<?php
namespace App\Repository;


trait BoardTrait
{
    private function convModelData($data)
    {
        foreach($data as $k => $v) {
            if(!empty($v)){
                $this[$k] = $v;
            }
        }

        return $this;
    }

}
