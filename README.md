# mageinterfacegen
Interface and model generator for Magento 2

This takes a basic interface structure such as the example below and spits out a useful interface and domain model for use in Magento 2. Note that it assumes everything to be a string so you'll need to review the resulting code just to be sure, but it makes the repetitive task of churning out new interfaces and models a lot easier. It doesn't enforce any naming conventions and will work with `camelCase` or `snake_case`.

Note that the output is printed to the console and it won't actually modify any files.

## Usage
`php mageinterfacegen.php path/to/interface.php`

What it expects in `path/to/interface.php` (the `extends ...` is optional, it only looks at the `interface ...` part):
```
namespace My\App\Namespace;

interface NewInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    const KEY_FIRST_ITEM = 'first_item';
    const KEY_SECOND_ITEM = 'secondItem';
    const THIRD_ITEM = 'third_item';
}

```
Which will lead to:

```
Processing NewInterface


-------------- begin interface --------------

    /**
     * @param string $firstItem
     * @return $this
     */
    public function setFirstItem($firstItem);

    /**
     * @return string
     */
    public function getFirstItem();

    /**
     * @param string $secondItem
     * @return $this
     */
    public function setSecondItem($secondItem);

    /**
     * @return string
     */
    public function getSecondItem();

    /**
     * @param string $thirdItem
     * @return $this
     */
    public function setThirdItem($thirdItem);

    /**
     * @return string
     */
    public function getThirdItem();



-------------- end interface --------------

**** When you've copied the above, press enter to generate the domain model




-------------- begin model --------------

    /**
     * @param string $firstItem
     * @return $this
     */
    public function setFirstItem($firstItem)
    {
        return $this->setData(self::KEY_FIRST_ITEM, $firstItem);
    }

    /**
     * @return string
     */
    public function getFirstItem()
    {
        return $this->getData(self::KEY_FIRST_ITEM);
    }

    /**
     * @param string $secondItem
     * @return $this
     */
    public function setSecondItem($secondItem)
    {
        return $this->setData(self::KEY_SECOND_ITEM, $secondItem);
    }

    /**
     * @return string
     */
    public function getSecondItem()
    {
        return $this->getData(self::KEY_SECOND_ITEM);
    }

    /**
     * @param string $thirdItem
     * @return $this
     */
    public function setThirdItem($thirdItem)
    {
        return $this->setData(self::THIRD_ITEM, $thirdItem);
    }

    /**
     * @return string
     */
    public function getThirdItem()
    {
        return $this->getData(self::THIRD_ITEM);
    }



-------------- end model --------------
```
