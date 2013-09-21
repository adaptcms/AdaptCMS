<?php

class InstallFilters
{
    public function afterInstallFilter()
    {
        $data['Categories']['News']['title'] = 'News';

        $data['Categories']['News']['Field'] = array(
            array(
                'title' => 'Test',
                'field_type' => 'text',
                'description' => '<p>This is a test field from the Sample theme.</p>'
            )
        );

        $data['Categories']['News']['Article'] = array(
            array(
                'title' => 'Sample Article',
                'ArticleValue' => array(
                    array(
                        'field_name' => 'Test',
                        'data' => '<p>This is a test, sample, example, etc.</p><p>From the Sample theme</p>'
                    )
                )
            )
        );

        return $data;
    }
}