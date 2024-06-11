<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart implements \ArrayAccess
{
    /** @var SessionInterface */
    protected $session;

    /** @var string */
    protected $key;

    public function __construct(SessionInterface $session, $key = '_cart')
    {
        $this->session = $session;
        $this->key = $key;
    }

    public function getItem($id)
    {
        return $this->session->get($this->key . '/' . $id);
    }

    public function getItems()
    {
        $allSessionData = $this->session->all();
        $cartItems = [];

        foreach ($allSessionData as $key => $value) {
            if (str_starts_with($key, '_cart/') && !str_starts_with($key, '_cart/totalPrice')) {
                $cartItems[$key] = $value;
            }
        }

        return $cartItems;
    }

    public function addItem($newItem)
    {
        $newItemId = $newItem->getId();
        $this->setItem($newItem);
        $this->updateTotalPrice();
    }

    public function addItems(array $items)
    {
        foreach ($items as $item) {
            $this->addItem($item);
        }
    }

    public function setItem($item)
    {
        if ($item->isValid() === false) {
            throw new \InvalidArgumentException('The item is not valid');
        }
        $this->session->set($this->key . '/' . $item->getId(), $item);
    }

    public function getTotalPrice()
    {
        return $this->session->get($this->key . '/totalPrice', 0);
    }

    public function updateTotalPrice()
    {
        $total = 0;
        foreach ($this->getItems() as $item) {
            $total += $item->getPrice();
        }
        $this->session->set($this->key . '/totalPrice', $total);
    }

    public function setItems(array $items)
    {
        foreach ($items as $item) {
            $this->setItem($item);
        }
        $this->updateTotalPrice();
    }

    public function removeItem($key)
    {
        $item = $this->session->remove($key);
        $this->updateTotalPrice();
        return $item;
    }

    public function hasItem($id)
    {
        return $this->session->has($this->key . '/' . $id);
    }

    public function count()
    {
        return count($this->getItems());
    }

    public function offsetExists($id)
    {
        return $this->hasItem($id);
    }

    public function offsetGet($id)
    {
        return $this->getItem($id);
    }

    public function offsetSet($id, $item)
    {
        if (is_null($id)) {
            $this->addItem($item);
        } elseif ($id === $item->getId()) {
            $this->setItem($item);
        } else {
            throw new \InvalidArgumentException('The index and id of the item must be the same');
        }
    }

    public function offsetUnset($id)
    {
        $this->removeItem($id);
    }

    public function clear()
    {
        $this->session->remove($this->key);
        $this->session->remove($this->key . '/totalPrice');
    }

    public function getTotal()
    {
        $total = 0;
        foreach ($this->getItems() as $item) {
            $total += $item->getPrice();
        }
        return $total;
    }
}
