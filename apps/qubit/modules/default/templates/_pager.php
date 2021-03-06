<div class="result-count">
  <?php if (0 < $pager->getNbResults()): ?>
    <?php echo __('Results %1% to %2% of %3%', array('%1%' => $pager->getFirstIndice(), '%2%' => $pager->getLastIndice(), '%3%' => $pager->getNbResults())) ?>
  <?php else: ?>
    <?php echo __('No results') ?>
  <?php endif; ?>
</div>

<?php if ($pager->haveToPaginate()): ?>
  <div class="pager section">

    <h2 class="element-invisible"><?php echo __('Pages') ?></h2>

    <div class="content">

      <?php if (1 < $pager->getPage()): ?>
        <?php echo link_to(__('Previous'), array('page' => $pager->getPage() - 1) + $sf_request->getParameterHolder()->getAll(), array('rel' => 'prev', 'title' => __('Go to previous page'))) ?>
      <?php endif; ?>

      <ol>
        <?php foreach ($pager->getLinks(10) as $page): ?>
          <?php if ($pager->getPage() == $page): ?>
            <li class="active"><?php echo $page ?></li>
          <?php else: ?>
            <li><?php echo link_to($page, array('page' => $page) + $sf_request->getParameterHolder()->getAll(), array('title' => __('Go to page %1%', array('%1%' => $page)))) ?></li>
          <?php endif; ?>
        <?php endforeach ?>
      </ol>

      <?php if ($pager->getLastPage() > $pager->getPage()): ?>
        <?php echo link_to(__('Next'), array('page' => $pager->getPage() + 1) + $sf_request->getParameterHolder()->getAll(), array('rel' => 'next', 'title' => __('Go to next page'))) ?>
      <?php endif; ?>

    </div>

  </div>
<?php endif; ?>
