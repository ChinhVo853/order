import React from "react";
import ReactDOM from "react-dom/client";
import { BrowserRouter, Route, Routes } from "react-router-dom";
import Test from "./components/test";

function Text() {
    return <h1>Hello from Text Component!</h1>;
}

function Example() {
    return (
        <React.StrictMode>
            <BrowserRouter>
                <Routes>
                    <Route path="/" element={<Test />} />
                    <Route path="/abc" element={<Text />} />
                </Routes>
            </BrowserRouter>
        </React.StrictMode>
    );
}

if (document.getElementById("example")) {
    const root = ReactDOM.createRoot(document.getElementById("example"));
    root.render(<Example />);
}
