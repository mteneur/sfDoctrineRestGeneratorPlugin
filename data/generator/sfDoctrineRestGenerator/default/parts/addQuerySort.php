
  /**
  * add sort to doctrine query
  * @param Doctrine_Query $q
  * @param array $params actions params
  * @return Doctrine_Query with the sort
  **/
  protected function addQuerySort(Doctrine_Query $q , $params)
  {
    <?php $sort_custom = $this->configuration->getValue('get.sort_custom'); ?>
    <?php $sort_default = $this->configuration->getValue('get.sort_default'); ?>
    <?php if ($sort_default && count($sort_default) == 2): ?>
    $sort = '<?php echo $sort_default[0] ?> <?php echo $sort_default[1] ?>';
    <?php endif; ?>
    <?php if ($sort_custom): ?>
    if (isset($params['sort_by']))
    {
        $sort = $params['sort_by'];
        unset($params['sort_by']);

        if (isset($params['sort_order']))
        {
            $sort .= ' '.$params['sort_order'];
            unset($params['sort_order']);
        }
    }
    <?php endif; ?>

    if (isset($sort))
    {
     $q->orderBy($sort);
    }

    return $q;
 }