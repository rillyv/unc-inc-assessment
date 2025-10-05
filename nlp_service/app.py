from flask import Flask, request, jsonify
import re
from collections import Counter

app = Flask(__name__)

@app.route("/analyze", methods=["POST"])
def analyze():
    data = request.get_json()
    text = data.get("content", "")

    if not text:
        return jsonify({"error": "content field is required"}), 400

    sentences = re.split(r'(?<=[.!?]) +', text.strip())
    summary = sentences[0] if sentences else ""

    words = re.findall(r'\b[a-zA-Z]{3,}\b', text.lower())
    stopwords = {"the", "and", "for", "that", "with", "this", "from", "have", "but", "not", "you", "are", "was"}
    filtered = [w for w in words if w not in stopwords]
    common = [w for w, _ in Counter(filtered).most_common(5)]

    return jsonify({
        "summary": summary,
        "keywords": common
    })

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000)

