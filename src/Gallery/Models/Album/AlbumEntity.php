<?php

namespace Gallery\Models\Album;

class AlbumEntity
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var int|null
     */
    protected $parent;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $path;

    /**
     * Field is used to store the tree left value
     *
     * @var int
     */
    protected $lft;

    /**
     * Field is used to store the tree right value
     *
     * @var int
     */
    protected $rgt;

    /**
     * Field is used to store the tree level value
     *
     * @var int
     */
    protected $lvl;

    /**
     * AlbumEntity constructor.
     * @param array $data
     */

    public function __construct(array $data)
    {
        $this->setData($data);
    }

    public function setData(array $data)
    {
        foreach ($data as $key => $datum) {
            $this->$key = $datum;
        }
    }

    /**
     * Return $album as associative massive
     *
     * @return array
     */
    public function getData(): array
    {
        $album = get_class_vars($this);
        return $album;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path)
    {
        $this->path = $path;
    }

    /**
     * @return int
     */
    public function getLft(): int
    {
        return $this->lft;
    }

    /**
     * @param int $lft
     */
    public function setLft(int $lft)
    {
        $this->lft = $lft;
    }

    /**
     * @return int
     */
    public function getRgt(): int
    {
        return $this->rgt;
    }

    /**
     * @param int $rgt
     */
    public function setRgt(int $rgt)
    {
        $this->rgt = $rgt;
    }

    /**
     * @return int
     */
    public function getLvl(): int
    {
        return $this->lvl;
    }

    /**
     * @param int $lvl
     */
    public function setLvl(int $lvl)
    {
        $this->lvl = $lvl;
    }


}