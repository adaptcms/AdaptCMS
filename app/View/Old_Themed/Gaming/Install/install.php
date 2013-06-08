<?php

class InstallFilters
{
    public function afterInstallFilter()
    {
        $data['Categories']['Games']['title'] = 'Games';
        $data['Categories']['Reviews']['title'] = 'Reviews';
        $data['Categories']['Platforms']['title'] = 'Platforms';

        $data['Categories']['Games']['Field'] = array(
            array(
                'title' => 'Developer',
                'field_type' => 'text',
                'description' => '<p>Enter in the name of the company who is developing this game.</p>'
            ),
            array(
                'title' => 'Publisher',
                'field_type' => 'text',
                'description' => '<p>Enter in the name of the company who is publishing this game.</p>'
            ),
            array(
                'title' => 'Genre',
                'field_type' => 'text',
                'description' => '<p>Enter in the name of the Genre of this game, such as "FPS" or "Action".</p>'
            ),
            array(
                'title' => 'Release Date',
                'field_type' => 'date',
                'description' => '<p>Select the release date of this game.</p>'
            ),
            array(
                'title' => 'ESRB',
                'field_type' => 'dropdown',
                'description' => '<p>Select the ESRB rating for this game.</p>',
                'field_options' => '["Early Childhood","Everyone","Everyone 10+","Teen","Mature","Adults Only","Rating Pending"]'
            ),
            array(
                'title' => 'Boxart',
                'field_type' => 'img',
                'description' => '<p>Select an image for the game boxart.</p>'
            )
        );

        $data['Categories']['Reviews']['Field'] = array(
            array(
                'title' => 'Review Text',
                'field_type' => 'textarea',
                'description' => '<p>Please enter in the contents of the review here.</p>',
                'required' => 1
            ),
            array(
                'title' => 'Score',
                'field_type' => 'num',
                'description' => '<p>Enter in the review score.</p>',
                'required' => 1
            )
        );

        $data['Categories']['Platforms']['Field'] = array(
            array(
                'title' => 'Release Date',
                'field_type' => 'date',
                'description' => '<p>Release Date of the system.</p>'
            ),
            array(
                'title' => 'System History',
                'field_type' => 'textarea',
                'description' => '<p>A short history of the system.</p>'
            ),
            array(
                'title' => 'System Icon',
                'field_type' => 'img',
                'description' => '<p>Pick an image to be used as the system icon.</p>'
            )
        );

        $data['Categories']['Platforms']['Article'] = array(
            array(
                'title' => 'Playstation 3',
                'tags' => '["ps3","playstation-3","sony-playstation-3","sony","playstation"]',
                'ArticleValue' => array(
                    array(
                        'field_name' => 'Release Date',
                        'data' => '2006-11-11'
                    ),
                    array(
                        'field_name' => 'System History',
                        'data' => '<p>The PlayStation 3 &nbsp;is a home video game console produced by Sony Computer Entertainment. It is the successor to the PlayStation 2, as part of the PlayStation series. The PlayStation 3 competes with Microsoft\'s Xbox 360 and Nintendo\'s Wii as part of the seventh generation of video game consoles. It was first released on November 11, 2006, in Japan, with international markets following shortly thereafter.</p>
<p>The console was first officially announced at E3 2005. Originally set for a spring 2006 release date, it was delayed several times until finally hitting stores at the end of the year. It was the first and currently only console to use Blu-ray Disc as its primary storage medium. Major features of the console include its unified online gaming service, the PlayStation Network, and its connectivity with the PlayStation Portable and PlayStation Vita, In September 2009 the updated PlayStation 3 Slim, was released. This Slim is lighter and thinner than the original version, although it lacks PlayStation 2 backwards compatibility (removed on later original models), but notably featured a re-designed logo and marketing design. A further refined Super Slim design was released in late 2012. As of November 4, 2012, 70 million PlayStation 3s have been sold worldwide. Its successor, PlayStation 4, is set for a Q4 2013 release.</p>
<p>Source: <a href="http://en.wikipedia.org/wiki/PlayStation_3" target="_blank">Wikipedia</a></p>'
                    )
                )
            ),
            array(
                'title' => 'XBOX 360',
                'tags' => '["xbox-360","360","xbox","microsoft","microsoft-xbox-360"]',
                'ArticleValue' => array(
                    array(
                        'field_name' => 'Release Date',
                        'data' => '2005-11-22'
                    ),
                    array(
                        'field_name' => 'System History',
                        'data' => '<p>The Xbox 360 is the second video game console developed by and produced for Microsoft and the successor to the Xbox. The Xbox 360 competes with Sony\'s PlayStation 3 and Nintendo\'s Wii as part of the seventh generation of video game consoles. As of September 30, 2012, 70 million Xbox 360 consoles have been sold worldwide.] The Xbox 360 was officially unveiled on MTV on May 12, 2005, with detailed launch and game information divulged later that month at the Electronic Entertainment Expo (E3). The console sold out completely upon release in all regions except in Japan.</p>
<p>Several major features of the Xbox 360 are its integrated Xbox Live service that allows players to compete online; download arcade games, game demos, trailers, TV shows, music and movies; and its Windows Media Center multimedia capabilities. The Xbox Live also offers access to various (often region-specific) third-party media streaming applications.</p>'
                    )
                )
            )
        );

        return $data;
    }
}