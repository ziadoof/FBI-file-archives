<?php include('inc/head.php');
    //  include('inc/delete.php');
?>
<?php
function show($files)
{
    $route=$files . '/';
    foreach(scandir($files) as $file) {
        if ($file != '.' && $file != '..') {
            if (isset(pathinfo($route.$file)['extension'])){
                if ( in_array(pathinfo($route.$file)['extension'],['html','txt','md'])){
                    echo "<a href='/index.php?route=$route$file'>$file</a>".'<a class="text-danger" href="/index.php?delete='.$route.$file.'"> | Supprimer</a><br>';
                } else {
                    echo $file.'<a class="text-danger" href="/index.php?delete=' . $route . $file . '"> | Supprimer</a>'.'<br>';
                }
            } else {
                echo '**'.$file .'<a class="text-danger"href="/index.php?delete=' . $route . $file . '"> | Supprimer</a>'.'<br>';
            }

            if (is_dir($route.$file)){
                show($route.$file);
            }
        }
    }
}
function delete($file)
{
    $route=$file . '/';
    if (is_dir($file))
    {
        foreach(scandir($file) as $fileName)
        {
            if ($fileName != '.' && $fileName != '..')
            {
                if (is_dir($route.$fileName))
                {
                    delete($route.$fileName);
                }
                else
                {
                    unlink($route.$fileName);
                }
            }
        }
        rmdir($file);
    } else
    {
        unlink($file);
    }
}
    if (isset($_GET['delete'])){
        $route = $_GET['delete'];
        delete($route);
    }
    if (isset($_POST['content'])){
        $route = $_POST['route'];
        $content =$_POST['content'];
        file_put_contents ( $route, $content);
    }
    show('files');
?>
    <form method="POST" action="index.php">
        <?php
        if (isset($_GET['route'])){
            $route = $_GET['route'];
            $content = file_get_contents($route);
            echo "<textarea name='content' cols='110' rows='15'>$content</textarea>";
            echo "<input type='submit' name='submit' value='Save'/>";
            echo "<input type='hidden' name='route' value='$route'>";
        }
        ?>
    </form>
<?php include('inc/foot.php'); ?>
