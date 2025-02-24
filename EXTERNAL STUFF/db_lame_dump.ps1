php yii migrate/create create_user_table --fields="name:string(100):notNull:unique,password_hash:string(64),access_token:string(16):notNull:unique,auth_key:string(32):notNull:unique";
php yii migrate/create create_pdf_file_table --fields="name:string(150):notNull:unique,bookmark:integer:notNull:defaultValue(1),user_id:integer:notNull:foreignKey(user)";
php yii migrate/create create_pdf_file_category_table --fields="user_id:integer:notNull:foreignKey(user),name:string(50):notNull,color:char(6):notNull"
php yii migrate/create create_pdf_file_category_entry_table --fields="pdf_file_id:int:notNull:foreignKey(pdf_file),category_id:int:notNull:foreignKey(pdf_file_category)"
php yii migrate/create add_slug_column_to_pdf_file_table --fields="slug:string(150):notNull:unique"