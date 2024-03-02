# Milvus Inscription

## Description
Magento 2 module, which adds the Inscription product attribute for 2 types of products: simple and configurable.

## API
The Inscription attribute could be changed via the API endpoint:
```
POST https://{{host}}/rest/V1/inscription/set
Authorization: Bearer {{token}}
Content-Type: application/json

{
    "product_id": 142,
    "value": "The BEST socks!"
}
```

## Logging
To enable or disable logging for changes made by, API go to:
```
Admin Panel > Stores > Settings > Configuration > catalog > Catalog > Product Inscriptions
```
and switch the ```Enable Logging``` option.
