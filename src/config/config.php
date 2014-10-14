<?php

return [
    /**
     * Specify the schema used by the translator
     */
    'schema' => [
        'table' => 'translations',
        'fields' => [
            'locale'    => 'locale',
            'group'     => 'group_name',
            'item'      => 'item',
            'content'   => 'content',
        ]
    ]
];