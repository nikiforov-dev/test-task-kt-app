<?php

namespace Tests\Resource\XmlData;

class BadWeightXml
{
    /**
     * @return string
     */
    public static function getXML(): string
    {
        return /** @lang text */
            <<<XML
<?xml version="1.0"?>
<products>
    <product>
        <name>in suscipit</name>
        <description>By this time she found herself at last it unfolded its arms, took the regular course.' 'What was that?' inquired Alice. 'Reeling and Writhing, of course, I meant,' the King very decidedly, and he.</description>
        <weight>30 t</weight>
        <category>et</category>
    </product>
</products>
XML;
    }
}