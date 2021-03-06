<?php 

class Part {

    /**
     * @var string
     */
    protected $viewPrefix = 'partials';

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $children = array();

    /**
     * @var array
     */
    protected $parameters = array();

    /**
     * @param $name
     * @param array $children
     * @param array $parameters
     */
    public function __construct( $name, $children = array(), $parameters = array() )
    {

        $this->name = $name;
        $this->parameters = $parameters;

        $this->addChildren($children);
    }

    /**
     * @param $name
     * @return bool
     */
    public function check( $name )
    {
        return $this->name === $name;
    }

    /**
     * @param Part $part
     * @return bool
     */
    public function same(Part $part)
    {
        return $this->check($part->name);
    }

    /**
     * @return array
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param $name
     */
    public function removeChild($name)
    {
        foreach($this->children as $key => $child)
        {
            if($child->check("{$this->name}.$name"))
            {
                unset($this->children[$key]);

                break;
            }
        }
    }

    /**
     * @param $name
     * @param array $children
     * @param array $parameters
     * @return static
     */
    public static function factory($name, array $children = array(), $parameters = array())
    {
        $part = new static($name, $children, $parameters);

        return $part;
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
        return ! empty($this->children);
    }

    /**
     * @param $children
     */
    public function addChildren( $children )
    {
        foreach($children as $child)

            $this->addChild($child);
    }

    /**
     * @param $child
     * @param bool|int $position
     */
    public function addChild( $child, $position = false )
    {
        if(! $child instanceof Part)
        {
            $child = static::factory($this->name . '.' .$child, array(), $this->parameters);
        }

        if($position === false) $this->children[] = $child;

        else
        {
            // First shift children
            for($i = count($this->children) - 1; $i >= $position; $i--)
            {
                $this->children[$i + 1] = $this->children[$i];
            }

            // Now you can safely add it
            $this->children[$position] = $child;
        }
    }

    /**
     * @return string
     */
    public function render()
    {

        return View::make($this->getViewName(), array('part' => $this))->__toString();
    }

    /**
     * @return string
     */
    public function getViewName()
    {
        $name = '';

        if($this->viewPrefix)
        {
            $name .= "{$this->viewPrefix}.";
        }

        if($this->hasChildren())
        {
            return "$name{$this->name}.template";
        }

        return "$name{$this->name}";
    }

    /**
     * @param $property
     * @return mixed
     */
    public function __get($property)
    {
        if(array_key_exists($property, $this->parameters))

            return $this->parameters[$property];

        return $this->$property;
    }

    /**
     * @param $property
     * @return bool
     */
    public function __isset($property)
    {
        return in_array($property, $this->parameters);
    }
}