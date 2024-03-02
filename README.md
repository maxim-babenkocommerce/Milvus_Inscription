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
    "value": "test socks sign"
}
```
