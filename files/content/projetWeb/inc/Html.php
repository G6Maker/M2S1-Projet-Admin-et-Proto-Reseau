<?php
class Html {
    public static function redirection($url){
        ?>
        <script>
            location.href = "<?php echo $url ?>";
        </script>
        <?php
        echo '<form action="' . $url . '">';
        echo '<button type="submit" class="btn btn-danger btn-lg btn-block">Echec de la redirection automatique</button>';
        echo '</form>';
    }

    public static function setTitle($title){
        ?>
        <script>
            document.title = "<?php echo $title; ?>";
        </script>
        <?php
    }

}
?>