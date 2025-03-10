<?= view("UI/head"); ?>
<?= view("UI/sidebar"); ?>

<div class="content-wrapper">
  <section class="content pt-3">
    <div class="container-fluid">
      <?= view($view); ?>
    </div><!-- /.container-fluid -->
  </section>
</div>

<?= view("UI/scripts"); ?>