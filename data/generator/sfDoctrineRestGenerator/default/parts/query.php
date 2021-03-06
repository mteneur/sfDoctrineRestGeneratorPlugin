  /**
   * Create the query for selecting objects, eventually along with related
   * objects
   *
   * @param   array   $params  an array of criterions for the selection
   */
  public function query($params)
  {
    $q = Doctrine_Query::create()
<?php
$display = $this->configuration->getValue('get.display');
$embed_relations = $this->configuration->getValue('get.embed_relations');

$fields = $display;
foreach ($embed_relations as $relation_name)
{
  $fields[] = $relation_name.'.*';
}
?>
<?php if (count($display) > 0): ?>
<?php $display = implode(', ', $fields); ?>
      ->select('<?php echo $display ?>')
<?php endif; ?>

      ->from($this->model.' '.$this->model)
<?php foreach ($embed_relations as $embed_relation): ?>
<?php if (!$this->isManyToManyRelation($embed_relation)): ?>

      ->leftJoin($this->model.'.<?php echo $embed_relation ?> <?php echo $embed_relation ?>')<?php endif; ?><?php endforeach; ?>;

<?php $primaryKeys = $this->getPrimaryKeys(); ?>
<?php foreach ($primaryKeys as $primaryKey): ?>
    if (isset($params['<?php echo $primaryKey ?>']))
    {
      $values = explode('<?php echo $this->configuration->getValue('default.separator', ',') ?>', $params['<?php echo $primaryKey ?>']);

      if (count($values) == 1)
      {
        $q->andWhere($this->model.'.<?php echo $primaryKey ?> = ?', $values[0]);
      }
      else
      {
        $q->whereIn($this->model.'.<?php echo $primaryKey ?>', $values);
      }

      unset($params['<?php echo $primaryKey ?>']);
    }

<?php endforeach; ?>
<?php $filters = $this->configuration->getFilters() ?>
<?php foreach ($filters as $name => $filter): ?>
<?php if (isset($filters[$name]['multiple']) && $filters[$name]['multiple']): ?>
    if (isset($params['<?php echo $name ?>']))
    {
      $values = explode('<?php echo $this->configuration->getValue('default.separator', ',') ?>', $params['<?php echo $name ?>']);

      if (count($values) == 1)
      {
        $q->andWhere($this->model.'.<?php echo $name ?> = ?', $values[0]);
      }
      else
      {
        $q->whereIn($this->model.'.<?php echo $name ?>', $values);
      }

      unset($params['<?php echo $name ?>']);
    }
<?php endif; ?>
<?php endforeach; ?>
    foreach ($params as $name => $value)
    {
      $q->andWhere($this->model.'.'.$name.' = ?', $value);
    }

    return $q;
  }
