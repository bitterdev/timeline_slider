<?php

namespace Concrete\Package\TimelineSlider;

use Concrete\Core\Package\Package;

class Controller extends Package
{
    protected $pkgHandle = 'timeline_slider';
    protected $pkgVersion = '0.0.3';
    protected $appVersionRequired = '9.0.0';
    
    public function getPackageDescription()
    {
        return t('Add support to a timeline slider to your site.');
    }

    public function getPackageName()
    {
        return t('Timeline Slider');
    }

    public function install()
    {
        $pkg = parent::install();
        $this->installContentFile("data.xml");
        return $pkg;
    }

    public function upgrade()
    {
        parent::upgrade();
        $this->installContentFile("data.xml");
    }
}