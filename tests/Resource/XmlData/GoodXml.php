<?php

namespace Tests\Resource\XmlData;

class GoodXml
{
    /**
     * @return int
     */
    public static function getProductsCount(): int
    {
        return 4;
    }

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
        <weight>30 g</weight>
        <category>et</category>
    </product>
    <product>
        <name>voluptate dolorem</name>
        <description>Gryphon whispered in reply, 'for fear they should forget them before the officer could get away without speaking, but at any rate,' said Alice: 'she's so extremely--' Just then her head in the.</description>
        <weight>22 kg</weight>
        <category>et</category>
    </product>
    <product>
        <name>delectus hic</name>
        <description>Pigeon. 'I'm NOT a serpent!' said Alice hastily; 'but I'm not particular as to size,' Alice hastily replied; 'only one doesn't like changing so often, of course was, how to begin.' For, you see.</description>
        <weight>36 kg</weight>
        <category>voluptas</category>
    </product>
    <product>
        <name>autem molestiae</name>
        <description>Gryphon. 'They can't have anything to say, she simply bowed, and took the opportunity of taking it away. She did it so yet,' said Alice; 'I might as well say,' added the Gryphon, with a sigh: 'it's.</description>
        <weight>77 kg</weight>
        <category>voluptate</category>
    </product>
</products>
XML;
    }
}