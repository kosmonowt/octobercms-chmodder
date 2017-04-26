<?php namespace KosmosKosmos\ChModder;

use System\Classes\PluginBase;

/**
 * ChModder Plugin Information File
 */
class Plugin extends PluginBase {

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'ChModder',
            'description' => 'For ChModding your installation. Access on both, console user and www-data',
            'author'      => 'Andreas Kosmowicz',
            'icon'        => 'icon-save'
        ];
    }

    public function registerReportWidgets() {
        return [
            'KosmosKosmos\ChModder\ReportWidgets\ChModder' => [
                'label'   => 'ChModder Widget',
                'context' => 'dashboard'
            ]
        ];
    }

}
