# CustomDB

## Database requirements

- Every table must have the id field ('id', INT, Primary key, Auto_increment)
- All fields must allow null values or have a default value (NULL/DEFAULT)
- The 2nd field will be used as display value (like a name)
- Supported SQL date types:
  - CHAR/VARCHAR (HTML type: text)
  - INT (signed) (HTML type: number)
  - DECIMAL (HTML type: number (with step))
  - DATE (HTML type: date)
  - BIT (HTML type: checkbox)

## Metadata (in database comments)

### Change field caption

- Enter a caption in the table/field comments surrounded by double quotes (like: "My caption")

### Hide field

- Enter a underscoe in the table/field comment to hide the field
