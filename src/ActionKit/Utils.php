<?php
namespace ActionKit;
use Twig_Loader_Filesystem;
use ReflectionClass;
use ActionKit\ColumnConvert;

class Utils
{

    public static function create_twig_fs_loader($dir)
    {
        return new Twig_Loader_Filesystem($dir);
    }

    public static function get_class_dir($class)
    {
        $ref = new ReflectionClass($class);
        return $this->_classDir = dirname($ref->getFilename());
    }

    public static function get_object_dir($object)
    {
        $ref = new ReflectionObject($object);
        return $this->_classDir = dirname($ref->getFilename());
    }
}



