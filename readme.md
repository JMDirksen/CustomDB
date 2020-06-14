# CustomDB

## Database requirements

- Every table must have the id field ('id', INT, Primary key, Auto_increment)
- All fields must allow null values or have a default value (NULL/DEFAULT)
- Supported SQL date types:
  - CHAR/VARCHAR (HTML type: text)
  - INT (signed) (HTML type: number)
  - DECIMAL (10,2) (HTML type: number step=0.01)
  - DATE (HTML type: date)
  - BIT (HTML type: checkbox)

## Metadata

- Table/field comments can be used for nice display names
- Hide table/field by starting the comment with an underscore
