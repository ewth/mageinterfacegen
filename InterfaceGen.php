<?php

namespace MageGen;

class InterfaceGen
{

    protected $debug = false;

    /**
     * @param bool $debug
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
    }

    public function generateInterface()
    {
        // define('MageGen::TAB', '    ');

        echo "Interface to process: ";
        if (!empty($argv[1])) {
            $filePath = $argv[1];
            echo $filePath . "\n";
        } else {
            $handle   = fopen("php://stdin", "r");
            $filePath = trim(fgets($handle));
            fclose($handle);
        }

        if (!file_exists($filePath)) {
            die("File '${filePath}' could not be found or is not accessible.\n");
        }

        $data = file_get_contents($filePath);
        $data = str_replace("\r\n", "\n", $data); // Grumble grumble Windows grumble

        if (empty($data) || !preg_match('#\ninterface (.[^ \n]+)#', $data, $matches)) {
            die("Interface could not be detected.\n");
        }

        $interfaceName = $matches[1];

        echo "Processing ${interfaceName}\n";

        if (!preg_match_all("#[\n\t ]const (.[^\n;]+)#", $data, $matches)) {
            die("const keys could not be found.\n");
        }

        $functions = $variables = $dataKeys = [];

        echo "\n\n-------------- begin interface --------------\n\n";

        foreach ($matches[1] as $item) {
            if (preg_match('#([a-zA-Z0-9\_]+).[^\=]*\=(.+)#', $item, $deconstruct)) {
                $dataKeys[]   = $deconstruct[1];
                $key          = preg_replace('#[^a-zA-Z0-9_]#', '', $deconstruct[2]);
                $variableName = $key;
                $functionName = null;

                if (preg_match('#[A-Z]#', $key)) {
                    $keys         = preg_split('#([A-Z])#', $key, -1, PREG_SPLIT_DELIM_CAPTURE);
                    $key          = ucwords($keys[0]);
                    $variableName = strtolower($keys[0]);
                    unset($keys[0]);
                    $key          .= implode('', $keys);
                    $variableName .= implode('', $keys);
                } else {
                    $variableName = $key;
                    $key          = ucwords($key);
                }
                $functionName = $key;

                // Split by _
                if (strpos($key, '_') !== false) {
                    $key          = explode('_', $key);
                    $keys         = [ucwords($key[0])];
                    $variableName = [strtolower($key[0])];
                    for ($i = 1; $i < count($key); $i++) {
                        $keys[]         = ucwords($key[$i]);
                        $variableName[] = ucwords($key[$i]);
                    }
                    $key          = $functionName = implode('', $keys);
                    $variableName = implode('', $variableName);
                }

                $functions[] = $functionName;
                $variables[] = $variableName;

                echo MageGen::TAB . '/**' . "\n";
                echo MageGen::TAB . ' * @param string $' . $variableName . "\n";
                echo MageGen::TAB . ' * @return $this' . "\n";
                echo MageGen::TAB . ' */' . "\n";
                echo MageGen::TAB . 'public function set' . $functionName . '($' . $variableName . ');' . "\n\n";
                echo MageGen::TAB . '/**' . "\n";
                echo MageGen::TAB . ' * @return string' . "\n";
                echo MageGen::TAB . ' */' . "\n";
                echo MageGen::TAB . 'public function get' . $functionName . '();' . "\n\n";
            }
        }

        echo "\n\n-------------- end interface --------------\n\n";
        echo "**** When you've copied the above, press enter to generate the domain model\n\n";
        $handle = fopen("php://stdin", "r");
        $temp   = fgets($handle);
        fclose($handle);

        echo "\n\n-------------- begin model --------------\n\n";

        foreach ($functions as $key => $function) {
            echo MageGen::TAB . '/**' . "\n";
            echo MageGen::TAB . ' * @param string $' . $variables[$key] . "\n";
            echo MageGen::TAB . ' * @return $this' . "\n";
            echo MageGen::TAB . ' */' . "\n";
            echo MageGen::TAB . 'public function set' . $function . '($' . $variables[$key] . ')' . "\n";
            echo MageGen::TAB . '{' . "\n";
            echo MageGen::TAB . MageGen::TAB . 'return $this->setData(self::' . $dataKeys[$key] . ', $' . $variables[$key] . ');' . "\n";
            echo MageGen::TAB . '}' . "\n\n";
            echo MageGen::TAB . '/**' . "\n";
            echo MageGen::TAB . ' * @return string' . "\n";
            echo MageGen::TAB . ' */' . "\n";
            echo MageGen::TAB . 'public function get' . $function . '()' . "\n";
            echo MageGen::TAB . '{' . "\n";
            echo MageGen::TAB . MageGen::TAB . 'return $this->getData(self::' . $dataKeys[$key] . ');' . "\n";
            echo MageGen::TAB . '}' . "\n\n";
        }

        echo "\n\n-------------- end model --------------\n\n";
    }
}
?>
