

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
    allocation_date DATE DEFAULT CURRENT_DATE,
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
    assignment_date DATE DEFAULT CURRENT_DATE,
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
    registration_date DATE DEFAULT CURRENT_DATE,
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