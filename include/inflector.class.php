<?php


class Inflector
{
    // ------ CLASS METHODS ------ //

    // ---- Public methods ---- //

    // {{{ pluralize()

    /**
    * Pluralizes English nouns.
    * 
    * @access public
    * @static
    * @param    string    $word    English noun to pluralize
    * @return string Plural noun
    */
    static function pluralize($word, $new_plural = null)
    {
        static $_cached;
        if(isset($new_plural)){
            $_cached[$word] = $new_plural;
            return;
        }
        $_original_word = $word;
        if(!isset($_cached[$_original_word])){
            $plural = array(
            '/(quiz)$/i' => '\1zes',
            '/^(ox)$/i' => '\1en',
            '/([m|l])ouse$/i' => '\1ice',
            '/(matr|vert|ind)ix|ex$/i' => '\1ices',
            '/(x|ch|ss|sh)$/i' => '\1es',
            '/([^aeiouy]|qu)ies$/i' => '\1y',
            '/([^aeiouy]|qu)y$/i' => '\1ies',
            '/(hive)$/i' => '\1s',
            '/(?:([^f])fe|([lr])f)$/i' => '\1\2ves',
            '/sis$/i' => 'ses',
            '/([ti])um$/i' => '\1a',
            '/(buffal|tomat)o$/i' => '\1oes',
            '/(bu)s$/i' => '\1ses',
            '/(alias|status)/i'=> '\1es',
            '/(octop|vir)us$/i'=> '\1i',
            '/(ax|test)is$/i'=> '\1es',
            '/s$/i'=> 's',
            '/$/'=> 's');

            $uncountable = array('equipment', 'information', 'rice', 'money', 'species', 'series', 'fish', 'sheep');

            $irregular = array(
            'person' => 'people',
            'man' => 'men',
            'child' => 'children',
            'sex' => 'sexes',
            'move' => 'moves');

            $lowercased_word = strtolower($word);

            if (in_array($lowercased_word,$uncountable)){
                return $word;
            }

            foreach ($irregular as $_plural=> $_singular){
                if (preg_match('/('.$_plural.')$/i', $word, $arr)) {
                    $_cached[$_original_word] = preg_replace('/('.$_plural.')$/i', substr($arr[0],0,1).substr($_singular,1), $word);
                    return $_cached[$_original_word];
                }
            }

            foreach ($plural as $rule => $replacement) {
                if (preg_match($rule, $word)) {
                    $_cached[$_original_word] = preg_replace($rule, $replacement, $word);
                    return $_cached[$_original_word];
                }
            }
            $_cached[$_original_word] = false;
            return false;
        }
        return $_cached[$_original_word];
    }

    // }}}
    // {{{ singularize()

    /**
    * Singularizes English nouns.
    * 
    * @access public
    * @static
    * @param    string    $word    English noun to singularize
    * @return string Singular noun.
    */
    static function singularize($word, $new_singular = null)
    {
        static $_cached;
        if(isset($new_singular)){
            $_cached[$word] = $new_singular;
            return;
        }
        $_original_word = $word;
        if(!isset($_cached[$_original_word])){
            $singular = array (
            '/(quiz)zes$/i' => '\\1',
            '/(matr)ices$/i' => '\\1ix',
            '/(vert|ind)ices$/i' => '\\1ex',
            '/^(ox)en/i' => '\\1',
            '/(alias|status|wax)es$/i' => '\\1',
            '/([octop|vir])i$/i' => '\\1us',
            '/(cris|ax|test)es$/i' => '\\1is',
            '/(shoe)s$/i' => '\\1',
            '/(o)es$/i' => '\\1',
            '/(bus)es$/i' => '\\1',
            '/([m|l])ice$/i' => '\\1ouse',
            '/(x|ch|ss|sh)es$/i' => '\\1',
            '/(m)ovies$/i' => '\\1ovie',
            '/(s)eries$/i' => '\\1eries',
            '/([^aeiouy]|qu)ies$/i' => '\\1y',
            '/([lr])ves$/i' => '\\1f',
            '/(tive)s$/i' => '\\1',
            '/(hive)s$/i' => '\\1',
            '/([^f])ves$/i' => '\\1fe',
            '/(^analy)ses$/i' => '\\1sis',
            '/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/i' => '\\1\\2sis',
            '/([ti])a$/i' => '\\1um',
            '/(n)ews$/i' => '\\1ews',
            '/s$/i' => '',
            );


            $uncountable = array('equipment', 'information', 'rice', 'money', 'species', 'series', 'fish', 'sheep','sms');

            $irregular = array(
            'person' => 'people',
            'man' => 'men',
            'child' => 'children',
            'sex' => 'sexes',
            'database' => 'databases',
            'move' => 'moves');

            $lowercased_word = strtolower($word);

            if (in_array($lowercased_word,$uncountable)){
                return $word;
            }

            foreach ($irregular as $_singular => $_plural){
                if (preg_match('/('.$_plural.')$/i', $word, $arr)) {
                    $_cached[$_original_word] = preg_replace('/('.$_plural.')$/i', substr($arr[0],0,1).substr($_singular,1), $word);
                    return $_cached[$_original_word];
                }
            }

            foreach ($singular as $rule => $replacement) {
                if (preg_match($rule, $word)) {
                    $_cached[$_original_word] = preg_replace($rule, $replacement, $word);
                    return $_cached[$_original_word];
                }
            }
            $_cached[$_original_word] = $word;
            return $word;
        }
        return $_cached[$_original_word];
    }

    // }}}
    // {{{ conditionalPlural()

    /**
     * Get the plural form of a word if first parameter is greater than 1
     *
     * @param integer $numer_of_records
     * @param string $word
     * @return string Pluralized string when number of items is greater than 1
     */
    function conditionalPlural($numer_of_records, $word)
    {
        return $numer_of_records > 1 ? Inflector::pluralize($word) : $word;
    }

    // }}}
    // {{{ titleize()

    /**
    * Converts an underscored or CamelCase word into a English
    * sentence.
    * 
    * The titleize function converts text like "WelcomePage",
    * "welcome_page" or  "welcome page" to this "Welcome
    * Page".
    * If second parameter is set to 'first' it will only
    * capitalize the first character of the title.
    * 
    * @access public
    * @static
    * @param    string    $word    Word to format as tile
    * @param    string    $uppercase    If set to 'first' it will only uppercase the
    * first character. Otherwise it will uppercase all
    * the words in the title.
    * @return string Text formatted as title
    */
    static function titleize($word, $uppercase = '')
    {
        $uppercase = $uppercase == 'first' ? 'ucfirst' : 'ucwords';
        return $uppercase(Inflector::humanize(Inflector::underscore($word)));
    }

    // }}}
    // {{{ camelize()

    /**
    * Returns given word as CamelCased
    * 
    * Converts a word like "send_email" to "SendEmail". It
    * will remove non alphanumeric character from the word, so
    * "who's online" will be converted to "WhoSOnline"
    * 
    * @access public
    * @static
    * @see variablize
    * @param    string    $word    Word to convert to camel case
    * @return string UpperCamelCasedWord
    */
    static function camelize($word)
    {
        static $_cached;
        if(!isset($_cached[$word])){
            if(preg_match_all('/\/(.?)/',$word,$got)){
                foreach ($got[1] as $k=>$v){
                    $got[1][$k] = '::'.strtoupper($v);
                }
                $word = str_replace($got[0],$got[1],$word);
            }
            $_cached[$word] = str_replace(' ','',ucwords(preg_replace('/[^A-Z^a-z^0-9^:]+/',' ',$word)));
        }
        return $_cached[$word];
    }

    // }}}
    // {{{ underscore()

    /**
    * Converts a word "into_it_s_underscored_version"
    * 
    * Convert any "CamelCased" or "ordinary Word" into an
    * "underscored_word".
    * 
    * This can be really useful for creating friendly URLs.
    * 
    * @access public
    * @static
    * @param    string    $word    Word to underscore
    * @return string Underscored word
    */
    static function underscore($word)
    {
        static $_cached;
        if(!isset($_cached[$word])){
            $_cached[$word] = strtolower(preg_replace(
            array('/[^A-Z^a-z^0-9^\/]+/','/([a-z\d])([A-Z])/','/([A-Z]+)([A-Z][a-z])/'),
            array('_','\1_\2','\1_\2'), $word));
        }
        return $_cached[$word];
    }


    // }}}
    // {{{ humanize()

    /**
    * Returns a human-readable string from $word
    * 
    * Returns a human-readable string from $word, by replacing
    * underscores with a space, and by upper-casing the initial
    * character by default.
    * 
    * If you need to uppercase all the words you just have to
    * pass 'all' as a second parameter.
    * 
    * @access public
    * @static
    * @param    string    $word    String to "humanize"
    * @param    string    $uppercase    If set to 'all' it will uppercase all the words
    * instead of just the first one.
    * @return string Human-readable word
    */
    static function humanize($word, $uppercase = '')
    {
        $uppercase = $uppercase == 'all' ? 'ucwords' : 'ucfirst';
        return $uppercase(str_replace('_',' ',preg_replace('/_id$/', '',$word)));
    }

    // }}}
    // {{{ variablize()

    /**
    * Same as camelize but first char is lowercased
    * 
    * Converts a word like "send_email" to "sendEmail". It
    * will remove non alphanumeric character from the word, so
    * "who's online" will be converted to "whoSOnline"
    * 
    * @access public
    * @static
    * @see camelize
    * @param    string    $word    Word to lowerCamelCase
    * @return string Returns a lowerCamelCasedWord
    */
    function variablize($word)
    {
        $word = Inflector::camelize($word);
        return strtolower($word[0]).substr($word,1);
    }

    // }}}
    // {{{ tableize()

    /**
    * Converts a class name to its table name according to rails
    * naming conventions.
    * 
    * Converts "Person" to "people"
    * 
    * @access public
    * @static
    * @see classify
    * @param    string    $class_name    Class name for getting related table_name.
    * @return string plural_table_name
    */
    function tableize($class_name)
    {
        return Inflector::pluralize(Inflector::underscore($class_name));
    }

    // }}}
    // {{{ classify()

    /**
    * Converts a table name to its class name according to Akelos
    * naming conventions.
    * 
    * Converts "people" to "Person"
    * 
    * @access public
    * @static
    * @see tableize
    * @param    string    $table_name    Table name for getting related ClassName.
    * @return string SingularClassName
    */
    public static function classify($table_name)
    {
        return Inflector::camelize(Inflector::singularize($table_name));
    }

    // }}}
    // {{{ ordinalize()

    /**
    * Converts number to its ordinal English form.
    * 
    * This method converts 13 to 13th, 2 to 2nd ...
    * 
    * @access public
    * @static
    * @param    integer    $number    Number to get its ordinal value
    * @return string Ordinal representation of given string.
    */
    function ordinalize($number)
    {
        if (in_array(($number % 100),range(11,13))){
            return $number.'th';
        }else{
            switch (($number % 10)) {
                case 1:
                    return $number.'st';
                    break;
                case 2:
                    return $number.'nd';
                    break;
                case 3:
                    return $number.'rd';
                default:
                    return $number.'th';
                    break;
            }
        }
    }

    // }}}

    /**
    * Removes the module name from a module/path, Module::name or Module_ControllerClassName.
    *
    *   Example:    Inflector::demodulize('admin/dashboard_controller');  //=> dashboard_controller
    *               Inflector::demodulize('Admin_DashboardController');  //=> DashboardController
    *               Inflector::demodulize('Admin::Dashboard');  //=> Dashboard
    */
    function demodulize($module_name)
    {
        $module_name = str_replace('::', '/', $module_name);
        return (strstr($module_name, '/') ? preg_replace('/^.*\//', '', $module_name) : (strstr($module_name, '_') ? substr($module_name, 1+strrpos($module_name,'_')) : $module_name));
    }
    
    /**
     * Transforms a string to its unaccented version. 
     * This might be useful for generating "friendly" URLs
     */
    function unaccent($text)
    {
        $map = array(
        'Ã€'=>'A', 'Ã'=>'A', 'Ã‚'=>'A', 'Ãƒ'=>'A', 'Ã„'=>'A', 'Ã…'=>'A', 'Ã†'=>'A', 'Ã‡'=>'C',
        'Ãˆ'=>'E', 'Ã‰'=>'E', 'ÃŠ'=>'E', 'Ã‹'=>'E', 'ÃŒ'=>'I', 'Ã'=>'I', 'ÃŽ'=>'I', 'Ã'=>'I',
        'Ã'=>'D', 'Ã‘'=>'N', 'Ã’'=>'O', 'Ã“'=>'O', 'Ã”'=>'O', 'Ã•'=>'O', 'Ã–'=>'O', 'Ã˜'=>'O',
        'Ã™'=>'U', 'Ãš'=>'U', 'Ã›'=>'U', 'Ãœ'=>'U', 'Ã'=>'Y', 'Ãž'=>'T', 'ÃŸ'=>'s', 'Ã '=>'a',
        'Ã¡'=>'a', 'Ã¢'=>'a', 'Ã£'=>'a', 'Ã¤'=>'a', 'Ã¥'=>'a', 'Ã¦'=>'a', 'Ã§'=>'c', 'Ã¨'=>'e',
        'Ã©'=>'e', 'Ãª'=>'e', 'Ã«'=>'e', 'Ã¬'=>'i', 'Ã­'=>'i', 'Ã®'=>'i', 'Ã¯'=>'i', 'Ã°'=>'e',
        'Ã±'=>'n', 'Ã²'=>'o', 'Ã³'=>'o', 'Ã´'=>'o', 'Ãµ'=>'o', 'Ã¶'=>'o', 'Ã¸'=>'o', 'Ã¹'=>'u',
        'Ãº'=>'u', 'Ã»'=>'u', 'Ã¼'=>'u', 'Ã½'=>'y', 'Ã¾'=>'t', 'Ã¿'=>'y');
        return str_replace(array_keys($map), array_values($map), $text);
    }


    function urlize($text)
    {
        return trim(Inflector::underscore(Inflector::unaccent($text)),'_');
    }

    /**
    * Returns $class_name in underscored form, with "_id" tacked on at the end. 
    * This is for use in dealing with the database.
    * 
    * @param string $class_name
    * @return string
    */
    function foreignKey($class_name, $separate_class_name_and_id_with_underscore = true)
    {
        return Inflector::underscore(Inflector::humanize(Inflector::underscore($class_name))).($separate_class_name_and_id_with_underscore ? "_id" : "id");
    }

    function toControllerFilename($name)
    {
        $name = str_replace('::', '/', $name);
        return AK_CONTROLLERS_DIR.DS.join(DS, array_map(array('Inflector','underscore'), strstr($name, '/') ? explode('/', $name) : array($name))).'_controller.php';
    }

    function toModelFilename($name)
    {
        return MODEL_DIR.DS.strtolower(Inflector::underscore($name)).'.php';
    }

    function toHelperFilename($name)
    {
        return AK_APP_DIR.DS.'helpers'.DS.Inflector::underscore($name).'_helper.php';
    }

    function toFullName($name, $correct)
    {
        if (strstr($name, '_') && (strtolower($name) == $name)){
            return Inflector::camelize($name);
        }

        if (preg_match("/^(.    * )({$correct})$/i", $name, $reg)){
            if ($reg[2] == $correct){
                return $name;
            }else{
                return ucfirst($reg[1].$correct);
            }
        }else{
            return ucfirst($name.$correct);
        }
    }

    function is_singular($singular)
    {
        return Inflector::singularize(Inflector::pluralize($singular)) == $singular;
    }
    
    function is_plural($plural)
    {
        return Inflector::pluralize(Inflector::singularize($plural)) == $plural;
    }

}