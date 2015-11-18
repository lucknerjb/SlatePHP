<?php

$baseDir = dirname(__FILE__);
$sourceDir = $baseDir . '/source';
$buildDir = $baseDir . '/build';
$distDir = $baseDir . '/dist';
$sourceDirs = ['fonts', 'images', 'javascripts', 'stylesheets'];

function printLine($line)
{
    echo "\t {$line}\n";
}

function rcopy($src, $dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . '/' . $file) ) {
                rcopy($src . '/' . $file,$dst . '/' . $file);
            }
            else {
                copy($src . '/' . $file,$dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

printLine('');
printLine('* Building SlatePHP project...');

// Make sure the build dir exists
if (!is_dir($buildDir)) {
    try {
        mkdir($buildDir, 0755);
    } catch(Exception $e) {
        echo $e->getMessage();
        die;
    }
    printLine('* Created "build" directory');
} else {
    printLine('* "build" directory already exists');
}

// Make sure the dist dir exists
if (!is_dir($distDir)) {
    try {
        mkdir($distDir, 0755);
    } catch(Exception $e) {
        echo $e->getMessage();
        die;
    }
    printLine('* Created "dist" directory');
} else {
    printLine('* "dist" directory already exists');
}

// Copy over the files from source to dist
printLine('* Copying over assets to build dir');
foreach($sourceDirs as $dir) {
    $fromLocation = "$sourceDir/$dir";
    $toLocation = "$buildDir/$dir";
    rcopy($fromLocation, $toLocation);
}
printLine('* Completed copying over assets to build dir');

// Parse the markdown
printLine('* Processing markdown source files');
exec("php {$sourceDir}/index.php | cat > {$buildDir}/index.html");
printLine('* Completed processing markdown source files');

// Replace the dist files
printLine('* Copying build to dist');
exec("rm -rf {$distDir}/*");
$fromLocation = "$buildDir";
$toLocation = "$distDir";
rcopy($fromLocation, $toLocation);
printLine('* Completed copying build to dist');

// Remove the build dir
exec("rm -fr {$buildDir}");
