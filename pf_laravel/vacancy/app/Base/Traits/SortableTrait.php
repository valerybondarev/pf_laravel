<?php

namespace App\Base\Traits;

use Exception;
use Rutorika\Sortable\SortableException;
use Rutorika\Sortable\SortableTrait as RutorikaSortableTrait;

trait SortableTrait
{
    use RutorikaSortableTrait {
        moveBefore as _moveBefore;
        moveAfter as _moveAfter;
    }


    public function moveToStart()
    {
        if ($first = $this->getPrevious()->first()) {
            $this->_moveBefore($first);
        }
    }


    public function moveToEnd()
    {
        if ($last = $this->getNext()->last()) {
            $this->_moveAfter($last);
        }
    }

    /**
     * @param      $id
     * @param null $primaryKey
     *
     * @throws SortableException
     */
    public function moveBeforeId($id, $primaryKey = null)
    {
        $primaryKey = $primaryKey ?: $this->getKeyName();

        if ($before = $this->newQuery()->where($primaryKey, $id)->first()) {
            $this->_moveBefore($before);
        }
    }

    /**
     * @param      $id
     * @param null $primaryKey
     *
     * @throws Exception
     */
    public function moveAfterId($id, $primaryKey = null)
    {
        $primaryKey = $primaryKey ?: $this->getKeyName();

        if ($before = $this->newQuery()->where($primaryKey, $id)->first()) {
            $this->_moveAfter($before);
        }
    }

    public function moveAfter($entity)
    {
        if ($entity === null) {
            $this->moveToEnd();
        } else {
            $this->_moveAfter($entity);
        }
    }

    public function moveBefore($entity)
    {
        if ($entity === null) {
            $this->moveToStart();
        } else {
            $this->_moveBefore($entity);
        }
    }
}
