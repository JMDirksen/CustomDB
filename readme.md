# CustomDB

## Database requirements

- Every table must have the id field ('id', INT, Primary key, Auto_increment)
- Supported SQL field types: CHAR/VARCHAR, INT (signed), DATE, BIT (HTML types respectively: text, number, date, checkbox)
- All fields must allow null values or have a default value (NULL/DEFAULT)

## Metadata

- Table/field comments can be used for nice display names
- Hide table/field by starting the comment with an underscore
