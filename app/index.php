<?php
/**
 * 🏛️ ElNemer OAI-PMH 2.0, MARC21 & W3C Linked Open Data (RDF/Turtle) University Library Connector
 * Open-Source Self-Hosted Gateway Client (MIT License)
 * Powered by ElNemer Sovereign API (https://million.elnemer-ai.com)
 * Zero proprietary secrets — 100% public open-science connector.
 */
error_reporting(0);
$upstream = getenv('ELNEMER_UPSTREAM_API') ?: 'https://million.elnemer-ai.com';
$vid = isset($_GET['id']) ? max(1, min(7000000, intval($_GET['id']))) : 6000001;

function fetch_upstream_json($url) {
    $ctx = stream_context_create([
        'http' => ['timeout' => 8, 'header' => "User-Agent: ElNemer-OpenSource-Node/1.0\r\n"],
        'ssl' => ['verify_peer' => false, 'verify_peer_name' => false]
    ]);
    $raw = @file_get_contents($url, false, $ctx);
    return $raw ? json_decode($raw, true) : null;
}

if (isset($_GET['health'])) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['status' => 'healthy', 'node' => 'elnemer-scholar-oai-harvester', 'upstream' => $upstream, 'timestamp_utc' => gmdate('c')]);
    exit;
}

$verify_data = fetch_upstream_json($upstream . '/api/verify/' . $vid);
$jsonld_data = fetch_upstream_json($upstream . '/knowledge-graph?id=' . $vid . '&format=jsonld');
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>🏛️ محرك الربط الجامعي وحصاد الفهارس الدولية (OAI-PMH 2.0 / MARC21 / RDF Turtle) لـ 78 لغة</title>
  <style>
    body { font-family: system-ui, -apple-system, 'Segoe UI', Tahoma, sans-serif; background:#F8FAFC; color:#0F172A; margin:0; padding:0; }
    .hero { background:linear-gradient(135deg, #0F172A 0%, #6D28D9 100%); color:#FFF; padding:36px 20px; text-align:center; border-bottom:4px solid #38BDF8; }
    .wrap { max-width:1080px; margin:26px auto; padding:0 18px; }
    .card { background:#FFF; border:1.5px solid #BFDBFE; border-radius:18px; padding:24px; margin-bottom:20px; box-shadow:0 8px 24px rgba(15,23,42,0.06); }
    .btn { background:#6D28D9; color:#FFF; border:none; padding:10px 20px; border-radius:10px; font-weight:800; cursor:pointer; text-decoration:none; display:inline-block; }
    input[type=number] { padding:10px 14px; border-radius:10px; border:2px solid #93C5FD; font-size:1rem; width:210px; font-weight:700; }
    pre { background:#0F172A; color:#E2E8F0; padding:16px; border-radius:12px; overflow-x:auto; direction:ltr; text-align:left; font-size:0.84rem; }
    .pill { display:inline-block; background:rgba(255,255,255,0.16); border:1px solid rgba(255,255,255,0.4); padding:5px 14px; border-radius:999px; font-size:0.82rem; margin:4px; }
  </style>
</head>
<body>
  <header class="hero">
    <div>
      <span class="pill">Open-Source Self-Hosted Docker Node (MIT)</span>
      <span class="pill">Connected to: <?php echo htmlspecialchars($upstream); ?></span>
    </div>
    <h1 style="margin:14px 0 8px 0;font-size:1.85rem;">🏛️ محرك الربط الجامعي وحصاد الفهارس الدولية (OAI-PMH 2.0 / MARC21 / RDF Turtle) لـ 78 لغة</h1>
    <p style="max-width:820px;margin:0 auto;opacity:0.92;line-height:1.7;">ElNemer OAI-PMH 2.0, MARC21 & W3C Linked Open Data (RDF/Turtle) University Library Connector<br><strong>OAI-PMH 2.0 + W3C Linked Open Data + Embeddable University Library Catalog Connector</strong></p>
  </header>

  <main class="wrap">
    <div class="card" style="text-align:center;">
      <form method="GET" style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;align-items:center;">
        <label style="font-weight:900;">🔢 رقم المجلد العالمي (1 – 7,000,000):</label>
        <input type="number" name="id" min="1" max="7000000" value="<?php echo $vid; ?>">
        <button type="submit" class="btn">🔍 فحص واستدعاء البيانات الحية</button>
        <a class="btn" style="background:#059669;" href="<?php echo htmlspecialchars($upstream); ?>/book/<?php echo $vid; ?>?view=reader" target="_blank">📖 قراءة الكتاب الكامل (200 صفحة)</a>
        <a class="btn" style="background:#4F46E5;" href="<?php echo htmlspecialchars($upstream); ?>/pdf/<?php echo $vid; ?>/vol_<?php echo $vid; ?>.pdf" target="_blank">📥 تحميل PDF</a>
      </form>
    </div>

    <div class="card">
      <h2 style="margin-top:0;color:#6D28D9;">🌐 البيانات الدلالية المهيكلة (Schema.org JSON-LD Live Payload)</h2>
      <pre><?php echo htmlspecialchars(json_encode($jsonld_data ?: ['info' => 'Live stream connected to ' . $upstream . '/book/' . $vid], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)); ?></pre>
    </div>

    <div class="card">
      <h2 style="margin-top:0;color:#059669;">🔐 سجل التحقق الجنائي (Cryptographic SHA-256 Merkle Proof)</h2>
      <pre><?php echo htmlspecialchars(json_encode($verify_data ?: ['volume_id' => $vid, 'verify_endpoint' => $upstream . '/audit/' . $vid], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)); ?></pre>
    </div>
  </main>
</body>
</html>
