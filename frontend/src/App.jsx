import React, { useState } from "react";

function App() {
  const [n, setN] = useState("");
  const [result, setResult] = useState(null);
  const [error, setError] = useState(null);
  const [loading, setLoading] = useState(false);

  const handleDistribute = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError(null);
    setResult(null);

    try {
      // 5c. Validation on frontend too?
      // "Input value does not exist or value is invalid"
      // If empty
      if (!n || n.trim() === "") {
        // We can fail fast or let backend handle it. Requirement 5c says "Program Input: In case input value is nil..."
        // which usually refers to the program receiving it. But frontend validation is good UX.
        // Let's let the backend do the heavy lifting to prove the backend works as per requirements,
        // but we can also catch obvious non-numeric issues.
      }

      const response = await fetch(
        `http://localhost:8000/index.php?n=${encodeURIComponent(n)}`,
      );
      const data = await response.json();

      if (!response.ok) {
        // Handle 400/500
        throw new Error(data.message || "Irregularity occurred");
      }

      if (data.status === "success") {
        setResult(data.data);
      } else {
        throw new Error(data.message || "Irregularity occurred");
      }
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="container">
      <div className="card-panel">
        <h1>Card Distributor</h1>

        <form onSubmit={handleDistribute}>
          <div className="input-group">
            <label htmlFor="people-count">Number of People</label>
            <div className="input-wrapper">
              <input
                id="people-count"
                type="text"
                value={n}
                onChange={(e) => setN(e.target.value)}
                placeholder="Enter a number (e.g. 4)"
                autoComplete="off"
              />
            </div>
          </div>

          <button type="submit" disabled={loading}>
            {loading ? "Distributing..." : "Distribute Cards"}
          </button>
        </form>

        {error && <div className="error-message">{error}</div>}

        {result && (
          <div className="result">
            {result.map((row, index) => (
              <div key={index} className="result-row">
                <span className="row-label">No. {index + 1}</span>
                <div className="cards">
                  {/* The requirement is "S-A,H-X". 
                      We display exactly that string, but maybe styled for readability.
                      Prompt: "Output format: ... S-A,H-X..."
                      Prompt: "The card distributed... will be separated (comma)"
                      The backend returns "S-A,H-X,..." strings.
                  */}
                  {row}
                </div>
              </div>
            ))}
          </div>
        )}
      </div>
    </div>
  );
}

export default App;
