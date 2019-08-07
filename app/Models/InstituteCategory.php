<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstituteCategory extends Model
{
    protected $fillable = ['name', 'have_parent'];

    public function instituteParentCategories()
    {
        return $this->hasMany(InstituteParentCategory::class, 'child_id');
    }

    public function selectedParentCategories()
    {
        $items = [];
        foreach ($this->instituteParentCategories as $item) {
            $parent = self::find($item->parent_id);
            $items[] = ['id' => $parent->id, 'name' => isset($parent) ? $parent->name : 'No data.'];
        }
        return $items;
    }

    public static function CreateItem($name, $have_parent)
    {
        return self::create(['name' => $name, 'have_parent' => $have_parent]);
    }

    public static function UpdateItem($id, string $name, string $have_parent)
    {
        $item = self::find($id);
        if (isset($item)) {
            $item->name = $name;
            $item->have_parent = $have_parent;
            $item->save();
            return $item;
        }
        return false;
    }

    public static function DeleteItem($id): ?bool
    {
        $item = self::find($id);
        if (isset($item)) {
            return $item->delete();
        }
        return false;
    }
}
