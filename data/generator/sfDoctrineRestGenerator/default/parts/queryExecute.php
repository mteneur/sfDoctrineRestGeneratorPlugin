  /**
   * Execute the query for selecting a collection of objects, eventually
   * along with related objects
   *
   * @param   array   $params  an array of criterions for the selection
   */
  public function queryExecute($params)
  {

    $query = $this->query($params);
    $query = $this->addQuerySort($query, $params);
    $query = $this->addQueryLimit($query, $params);


  $this->objects = $this->dispatcher->filter(
      new sfEvent(
        $this,
        'sfDoctrineRestGenerator.filter_results',
        array()
      ),
      $query->execute(array(), Doctrine::HYDRATE_ARRAY)
    )->getReturnValue();
  }
