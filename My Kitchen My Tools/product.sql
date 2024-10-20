CREATE TABLE products (
    product_id INT PRIMARY KEY AUTO_INCREMENT,
    product_name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    company_name VARCHAR(255) NOT NULL,
    product_size VARCHAR(50),  -- This can be NULL
    product_link VARCHAR(500) NOT NULL,
    product_description TEXT NOT NULL,  -- Product description
    image VARCHAR(255) DEFAULT NULL,  -- Image URL or path for the product
    category_name VARCHAR(255) NOT NULL,  -- Category name instead of foreign key reference
    hotseller BOOLEAN DEFAULT FALSE,  -- Indicates if the product is a hot seller
    new BOOLEAN DEFAULT FALSE,  -- Indicates if the product is a new product
    product_height VARCHAR(50),  -- Product height, can be NULL
    product_length VARCHAR(50),  -- Product length, can be NULL
    product_width VARCHAR(50),  -- Product width, can be NULL
    product_weight VARCHAR(50),  -- Product weight, can be NULL
    product_diameter VARCHAR(50),  -- Product diameter, can be NULL
    product_capacity VARCHAR(50),  -- Product capacity, can be NULL
    induction_base_diameter VARCHAR(50),  -- Induction base diameter, can be NULL
    product_material VARCHAR(100)  -- Product material, can be NULL
);
