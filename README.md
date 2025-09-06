# Disaster Management System

A comprehensive, database-driven **Disaster Management System** designed to streamline coordination and resource management during natural disasters. This MySQL-based backend system helps emergency responders track events, allocate resources, manage personnel, operate shelters, and coordinate logistics from a single, centralized platform.


## ✨ Features

- **Disaster Event Tracking**: Log and monitor ongoing disaster events (type, location, severity, start/end dates).
- **Resource Inventory Management**: Maintain a real-time inventory of critical supplies (food, water, medical kits, shelter materials) and track their allocation to specific events.
- **Personnel Management**: Coordinate emergency staff and volunteers by assigning them to roles based on their skills and availability status.
- **Shelter Management**: Set up shelters, define their capacities, register residents, and monitor occupancy levels in real-time.
- **Transportation Logistics**: Manage a fleet of vehicles (ambulances, trucks, boats) and assign them to disaster events.
- **Public Communication Portal**: A built-in system for receiving and managing messages from the public (aid requests, volunteer registrations).
- **Advanced SQL Functions**: Includes powerful user-defined functions for generating key operational insights automatically.


## 🗃️ Database Schema

The system is built on a normalized relational database with the following core tables:
*   `Disaster_Events` - Core table for disaster information.
*   `Resources` & `Resource_Allocation` - Manage inventory and distribution.
*   `Personnel` & `Personnel_Assignment` - Manage human resources.
*   `Shelters` & `Shelter_Residents` - Manage shelter operations.
*   `Transportation` - Manages vehicles and drivers.
*   `Contact_Messages` - Logs inquiries from the public.


## 🧠 Advanced SQL Features

This project demonstrates advanced SQL concepts through User-Defined Functions (UDFs):

*   `calculate_shelter_occupancy(shelter_id)` → `DECIMAL(5,2)`
    *   Calculates the current occupancy percentage of a shelter.
*   `get_available_quantity(resource_id)` → `INT`
    *   Returns the truly available quantity of a resource after subtracting all allocations.
*   `count_active_personnel(event_id)` → `INT`
    *   Counts how many personnel are actively assigned to a specific event.
*   `is_event_active(event_id)` → `BOOLEAN`
    *   Determines if a disaster event is currently ongoing.


## 🚀 Purpose & Use Case

This project serves as a robust **backend foundation** for a full-stack Disaster Management Application. It demonstrates:
*   **Advanced Database Design**: Modeling complex, real-world operational scenarios.
*   **SQL Proficiency**: Using DDL, DML, complex joins, and user-defined functions.
*   **Practical Application**: Solving a critical real-world problem with technology.

It is ideally suited for integration with a web frontend built using PHP, Python (Django/Flask), Node.js, or Java.


## 🔮 Future Enhancements

Potential features to extend this project:
*   A web-based frontend for intuitive user interaction.
*   Automated alerting and notification system.
*   GIS integration for mapping disaster events and resources.
*   Reporting dashboard with data visualizations (charts, graphs).
*   User authentication and role-based access control (e.g., Admin, Coordinator, Volunteer).

