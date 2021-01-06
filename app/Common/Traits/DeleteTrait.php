<?php

namespace App\Common\Traits;

use App\Common\Scopes\DeleteScope;
use App\Common\Constants\DeleteConst;

trait DeleteTrait
{
    /**
     *
     * @author zxf
     * @date   2021年1月6日
     */
    public static function bootDeleteTrait()
    {
        static::addGlobalScope(new DeleteScope());
    }

    /**
     *
     * @author zxf
     * @date   2021年1月6日
     */
    protected function performDeleteOnModel()
    {
        $query = $this->setKeysForSaveQuery($this->newModelQuery());
        $time = $this->freshTimestamp();
        $columns = [$this->getDeletedAtColumn() => DeleteConst::YES];
        $this->{$this->getDeletedAtColumn()} = DeleteConst::YES;

        if ($this->timestamps && ! is_null($this->getUpdatedAtColumn())) {
            $this->{$this->getUpdatedAtColumn()} = $time;
            $columns[$this->getUpdatedAtColumn()] = $this->fromDateTime($time);
        }

        $query->update($columns);
        $this->syncOriginalAttributes(array_keys($columns));
    }

    /**
     *
     * @author zxf
     * @date   2021年1月6日
     * @return boolean
     */
    public function restore()
    {
        // If the restoring event does not return false, we will proceed with this
        // restore operation. Otherwise, we bail out so the developer will stop
        // the restore totally. We will clear the deleted timestamp and save.
        if ($this->fireModelEvent('restoring') === false) {
            return false;
        }

        $this->{$this->getDeletedAtColumn()} = DeleteConst::NOT;

        // Once we have saved the model, we will fire the "restored" event so this
        // developer will do anything they need to after a restore operation is
        // totally finished. Then we will return the result of the save call.
        $this->exists = true;

        $result = $this->save();
        $this->fireModelEvent('restored', false);

        return $result;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月6日
     * @return string
     */
    public function getDeletedAtColumn()
    {
        return defined('static::DELETED_AT') ? static::DELETED_AT : 'delete_id';
    }

    /**
     *
     * @author zxf
     * @date   2021年1月6日
     * @return mixed
     */
    public function getQualifiedDeletedAtColumn()
    {
        return $this->qualifyColumn($this->getDeletedAtColumn());
    }
}
