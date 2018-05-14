# interfacegen
Interface and model generator for Magento 2

This takes a basic interface structure such as the example below and spits out a useful interface and domain model for use in Magento 2. Note that it assumes everything to be a string so you'll need to review the resulting code just to be sure, but it makes the repetitive task of churning out new interfaces and models a lot easier. It doesn't enforce any naming conventions and will work with `camelCase` or `snake_case`.

Note that the output is printed to the console and it won't actually modify any files.

## Usage
`php interfacegen.php path/to/interface.php`

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
