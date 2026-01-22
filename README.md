# Card Distributor

This project is a web application that distributes a standard deck of 52 playing cards to `n` people.

## Tech Stack

- **Frontend**: React (Vite), CSS (Modules/Vanilla)
- **Backend**: PHP 8.2
- **Environment**: Docker & Docker Compose

## Requirements

- Docker and Docker Compose installed on your machine.

## How to Run

1. Open a terminal in the project root directory.
2. Run the following command to build and start the application:
   ```bash
   docker compose up --build
   ```
3. Once the services are running, open your browser (Chrome recommended).
4. Navigate to: [http://localhost:5173](http://localhost:5173)

## Usage

1. Enter a number `n` (number of people) in the input field.
2. Click "Distribute Cards".
3. The distributed cards for each person will be displayed below.

## Error Handling

- If the input is invalid (empty, non-numeric, less than 0), an error message "Input value does not exist or value is invalid" is displayed.
- For other system irregularities, "Irregularity occurred" is displayed.

## Development Notes

- **Backend**: Located in `backend/index.php`. Handles the logic for deck creation, shuffling, and distribution.
- **Frontend**: Located in `frontend/`. A React application that consumes the PHP API.
- **Docker**: `docker-compose.yml` orchestrates the PHP backend (port 8000) and Node/Vite frontend (port 5173).

## Time Spent

Total time spent: Approximately 45 minutes.
