DROP DATABASE IF EXISTS disaster_management;
CREATE DATABASE disaster_management;
USE disaster_management;

CREATE TABLE Disaster_Events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(50) NOT NULL,
    location VARCHAR(100) NOT NULL,
    severity VARCHAR(20) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE
);

CREATE TABLE Resources (
    resource_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    type VARCHAR(50) NOT NULL,
    quantity_available INT NOT NULL,
    unit VARCHAR(20) NOT NULL
);

CREATE TABLE Resource_Allocation (
    allocation_id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    resource_id INT NOT NULL,
    quantity_used INT NOT NULL,
    allocation_date DATE DEFAULT (CURRENT_DATE),
    FOREIGN KEY (event_id) REFERENCES Disaster_Events(event_id) ON DELETE CASCADE,
    FOREIGN KEY (resource_id) REFERENCES Resources(resource_id) ON DELETE CASCADE
);

CREATE TABLE Personnel (
    personnel_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    role VARCHAR(50) NOT NULL,
    contact_number VARCHAR(20) NOT NULL,
    status VARCHAR(20) DEFAULT 'Available' CHECK (status IN ('Available', 'Assigned', 'Unavailable'))
);

CREATE TABLE Personnel_Assignment (
    assignment_id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    personnel_id INT NOT NULL,
    assigned_role VARCHAR(50) NOT NULL,
    assignment_date DATE DEFAULT (CURRENT_DATE),
    FOREIGN KEY (event_id) REFERENCES Disaster_Events(event_id) ON DELETE CASCADE,
    FOREIGN KEY (personnel_id) REFERENCES Personnel(personnel_id) ON DELETE CASCADE
);

CREATE TABLE Shelters (
    shelter_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    location VARCHAR(100) NOT NULL,
    capacity INT NOT NULL,
    current_occupancy INT DEFAULT 0,
    event_id INT NOT NULL,
    FOREIGN KEY (event_id) REFERENCES Disaster_Events(event_id) ON DELETE CASCADE
);

CREATE TABLE Shelter_Residents (
    resident_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    age INT,
    gender VARCHAR(20),
    shelter_id INT NOT NULL,
    registration_date DATE DEFAULT (CURRENT_DATE),
    FOREIGN KEY (shelter_id) REFERENCES Shelters(shelter_id) ON DELETE CASCADE
);

CREATE TABLE Transportation (
    vehicle_id INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(50) NOT NULL,
    driver_name VARCHAR(100) NOT NULL,
    availability TINYINT(1) DEFAULT 1,
    event_id INT NOT NULL,
    FOREIGN KEY (event_id) REFERENCES Disaster_Events(event_id) ON DELETE CASCADE
);

CREATE TABLE Contact_Messages (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    submission_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(20) DEFAULT 'unread' CHECK (status IN ('unread', 'read'))
);

-- Sample data for Contact_Messages


-- Sample Data for Disaster_Events 
INSERT INTO Disaster_Events (type, location, severity, start_date, end_date) VALUES
('Flood', 'Sylhet', 'Severe', '2023-06-15', '2023-07-10'),
('Cyclone', 'Cox''s Bazar', 'Extreme', '2023-05-20', '2023-05-25'),
('Landslide', 'Chittagong Hill Tracts', 'High', '2023-07-01', NULL);

-- Sample Data for Resources
INSERT INTO Resources (name, type, quantity_available, unit) VALUES
('Oral Saline', 'Medical', 10000, 'packs'),
('Rice Bags', 'Food', 5000, 'kg'),
('Bottled Water', 'Water', 20000, 'liters'),
('Tarpaulin Sheets', 'Shelter', 3000, 'units'),
('Mosquito Nets', 'Health', 8000, 'units');

-- Sample Data for Personnel 
INSERT INTO Personnel (name, role, contact_number, status) VALUES
('Abdul Rahman', 'Medical Officer', '+8801712345678', 'Available'),
('Fatima Begum', 'Logistics Coordinator', '+8801811122334', 'Assigned'),
('Kamal Hossain', 'Rescue Specialist', '+8801911223344', 'Available'),
('Ayesha Akter', 'Field Nurse', '+8801722334455', 'Unavailable');

-- Sample Data for Shelters 
INSERT INTO Shelters (name, location, capacity, current_occupancy, event_id) VALUES
('Sylhet Govt. College', 'Sylhet City', 1200, 850, 1),
('Cox''s Bazar High School', 'Cox''s Bazar', 800, 800, 2),
('Rangamati Central Shelter', 'Rangamati', 500, 320, 3);

-- Sample Data for Shelter_Residents 
INSERT INTO Shelter_Residents (name, age, gender, shelter_id) VALUES
('Mohammad Ali', 45, 'Male', 1),
('Nusrat Jahan', 28, 'Female', 1),
('Sanjay Chakma', 32, 'Male', 3),
('Anika Rahman', 5, 'Female', 2),
('Rahim Uddin', 67, 'Male', 1);

-- Sample Data for Transportation
INSERT INTO Transportation (type, driver_name, availability, event_id) VALUES
('Ambulance', 'Shahidul Islam', TRUE, 1),
('Supply Truck', 'Karim Mia', FALSE, 2),
('Rescue Boat', 'Abdul Gofur', TRUE, 3),
('Bus', 'Faruk Ahmed', TRUE, 1);

-- Sample Data for Personnel_Assignment
INSERT INTO Personnel_Assignment (event_id, personnel_id, assigned_role, assignment_date) VALUES
(1, 1, 'Medical Support', '2023-06-15'),
(1, 2, 'Supply Distribution', '2023-06-15'),
(2, 3, 'Rescue Operations', '2023-05-20'),
(2, 4, 'Medical Support', '2023-05-20'),
(3, 1, 'Medical Support', '2023-07-01'),
(3, 3, 'Evacuation Lead', '2023-07-01');

-- Sample Data for Resource_Allocation
INSERT INTO Resource_Allocation (event_id, resource_id, quantity_used, allocation_date) VALUES
(1, 1, 2000, '2023-06-15'),  -- Oral Saline for Flood
(1, 2, 1000, '2023-06-16'),  -- Rice Bags for Flood
(1, 3, 5000, '2023-06-15'),  -- Bottled Water for Flood
(2, 4, 1000, '2023-05-20'),  -- Tarpaulin Sheets for Cyclone
(2, 5, 2000, '2023-05-21'),  -- Mosquito Nets for Cyclone
(3, 2, 500, '2023-07-01'),   -- Rice Bags for Landslide
(3, 3, 2000, '2023-07-01');  -- Bottled Water for Landslide


-- Add some sample messages
INSERT INTO Contact_Messages (name, email, subject, message) VALUES
('John Doe', 'john@example.com', 'Emergency Support Needed', 'We need immediate assistance in flood affected area.'),
('Sarah Smith', 'sarah@example.com', 'Volunteer Registration', 'I would like to register as a volunteer for disaster relief.'),
('Mike Johnson', 'mike@example.com', 'Resource Request', 'Requesting medical supplies for cyclone victims.');


-- User-defined Functions
DELIMITER //

-- Drop existing functions first
DROP FUNCTION IF EXISTS calculate_shelter_occupancy //
DROP FUNCTION IF EXISTS get_available_quantity //
DROP FUNCTION IF EXISTS count_active_personnel //
DROP FUNCTION IF EXISTS is_event_active //

-- Function to calculate shelter occupancy percentage
CREATE FUNCTION calculate_shelter_occupancy(shelter_id_param INT) 
RETURNS DECIMAL(5,2)
READS SQL DATA
BEGIN
    DECLARE total_capacity INT DEFAULT 0;
    DECLARE current_occ INT DEFAULT 0;
    DECLARE occupancy_rate DECIMAL(5,2) DEFAULT 0;
    
    SELECT capacity, current_occupancy 
    INTO total_capacity, current_occ
    FROM Shelters 
    WHERE shelter_id = shelter_id_param;
    
    IF total_capacity > 0 THEN
        SET occupancy_rate = (current_occ / total_capacity) * 100;
    END IF;
    
    RETURN COALESCE(occupancy_rate, 0);
END //

-- Function to get available quantity
CREATE FUNCTION get_available_quantity(resource_id_param INT) 
RETURNS INT
READS SQL DATA
BEGIN
    DECLARE total_qty INT DEFAULT 0;
    DECLARE used_qty INT DEFAULT 0;
    
    SELECT quantity_available INTO total_qty
    FROM Resources 
    WHERE resource_id = resource_id_param;
    
    SELECT COALESCE(SUM(quantity_used), 0) INTO used_qty
    FROM Resource_Allocation
    WHERE resource_id = resource_id_param;
    
    RETURN COALESCE(total_qty - used_qty, 0);
END //

-- Function to count active personnel for an event
CREATE FUNCTION count_active_personnel(event_id_param INT) 
RETURNS INT
READS SQL DATA
BEGIN
    DECLARE personnel_count INT DEFAULT 0;
    
    SELECT COUNT(DISTINCT p.personnel_id) INTO personnel_count
    FROM Personnel p
    JOIN Personnel_Assignment pa ON p.personnel_id = pa.personnel_id
    WHERE pa.event_id = event_id_param
    AND p.status = 'Assigned';
    
    RETURN personnel_count;
END //

-- Function to check if event is active
CREATE FUNCTION is_event_active(event_id_param INT) 
RETURNS BOOLEAN
READS SQL DATA
BEGIN
    DECLARE end_date_val DATE;
    
    SELECT end_date INTO end_date_val
    FROM Disaster_Events
    WHERE event_id = event_id_param;
    
    RETURN (end_date_val IS NULL OR end_date_val >= CURDATE());
END //

DELIMITER ;



-- Add sample usage comments
/*
-- Test shelter occupancy
SELECT calculate_shelter_occupancy(1) AS occupancy_rate;

-- Test available quantity
SELECT get_available_quantity(1) AS available_water_bottles;

-- Test active personnel count
SELECT count_active_personnel(1) AS active_staff;

-- Test event status
SELECT is_event_active(1) AS is_active;
*/