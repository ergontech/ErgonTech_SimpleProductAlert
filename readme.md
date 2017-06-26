# ErgonTech_SimpleProductAlert

Allows customers to sign up for stock stock alerts for simple configurations of configurable products.

## Installation

```json
{
    "type": "vcs",
    "url": "https://github.com/ergontech/ErgonTech_SimpleProductAlert"
}
```

Require the module by running the following command:
```bash
composer require ergontech/simpleproductalert:dev-master
```

## Usage

### Candidate Predicate Logic
The default "stock predicate" (which determines whether or not a product is a candidate for stock notifications) uses Mage_ProductAlert's default logic. That logic can be overridden by declaring the following XML node:
```xml
<config>
    <simpleproductalert>
        <stock_predicate>
            <class>Your_Class_Here</class>
            <function>staticFunctionName</function>
        </stock_predicate>
    </simpleproductalert>
</config>
```