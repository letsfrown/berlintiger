(async () => {
  await figma.loadAllPagesAsync();

  const page =
    figma.root.children.find((p) => p.name.includes("Implemented")) ||
    figma.currentPage;

  await figma.setCurrentPageAsync(page);

  const get = (name) => page.children.find((c) => c.name === name);

  const foundations = get("BT / Foundations");
  const components = get("BT / Components");
  const buttonSet = get("BT / Button");
  const tabSet = get("BT / Spielplan Tab");
  const headerSet = get("BT / Header");
  const logoTiger = get("BT / Logo / Tiger");
  const logoDbv = get("BT / Logo / DBV");
  const home = get("BT / Screen / Home — Desktop");
  const team = get("BT / Screen / Team — Desktop");
  const dbv = get("BT / Screen / DBV — Desktop");

  // Hide raw logo uploads
  for (const n of page.children) {
    if (/berlintiger-logo/i.test(n.name)) {
      n.visible = false;
      n.x = -5000;
      n.y = 0;
    }
  }

  // Remove old section labels
  for (const n of [...page.children]) {
    if (n.name.startsWith("BT / Section /")) n.remove();
  }

  await figma.loadFontAsync({ family: "Inter", style: "Semi Bold" });

  function label(name, x, y, text) {
    const t = figma.createText();
    t.name = name;
    t.fontName = { family: "Inter", style: "Semi Bold" };
    t.fontSize = 28;
    t.characters = text;
    t.fills = [{ type: "SOLID", color: { r: 0, g: 0, b: 0 } }];
    t.x = x;
    t.y = y;
    return t;
  }

  const COL = 80;

  // Row 1 — Foundations + Components showcase
  label("BT / Section / 01 Foundations", 0, 0, "01 — Foundations");
  if (foundations) {
    foundations.x = 0;
    foundations.y = 48;
  }

  const right = foundations ? foundations.x + foundations.width + COL : 1200;
  label("BT / Section / 02 Components", right, 0, "02 — Components");
  if (components) {
    components.x = right;
    components.y = 48;
  }

  const row1Bottom = Math.max(
    foundations ? foundations.y + foundations.height : 0,
    components ? components.y + components.height : 0
  );

  // Row 2 — Component masters
  const mastersY = row1Bottom + 100;
  label("BT / Section / 02b Masters", 0, mastersY - 40, "Component masters");

  let mx = 0;
  for (const m of [buttonSet, tabSet, headerSet, logoTiger, logoDbv]) {
    if (!m) continue;
    m.x = mx;
    m.y = mastersY;
    mx += m.width + 64;
  }

  const mastersBottom = Math.max(
    mastersY,
    ...[buttonSet, tabSet, headerSet, logoTiger, logoDbv]
      .filter(Boolean)
      .map((m) => m.y + m.height)
  );

  // Row 3 — Screens
  const screensY = mastersBottom + 100;
  label("BT / Section / 03 Screens", 0, screensY - 40, "03 — Screens (Desktop)");

  let sx = 0;
  for (const s of [home, team, dbv]) {
    if (!s) continue;
    s.x = sx;
    s.y = screensY;
    sx += s.width + 64;
  }

  // Layer order: labels + content bottom → top
  const order = [
    "BT / Section / 01 Foundations",
    "BT / Foundations",
    "BT / Section / 02 Components",
    "BT / Components",
    "BT / Section / 02b Masters",
    "BT / Button",
    "BT / Spielplan Tab",
    "BT / Header",
    "BT / Logo / Tiger",
    "BT / Logo / DBV",
    "BT / Section / 03 Screens",
    "BT / Screen / Home — Desktop",
    "BT / Screen / Team — Desktop",
    "BT / Screen / DBV — Desktop",
  ];
  for (const name of order) {
    const n = get(name);
    if (n) page.appendChild(n);
  }

  figma.viewport.scrollAndZoomIntoView(
    [foundations, components, home, team, dbv].filter(Boolean)
  );

  figma.notify("BT layout tidied");
  figma.closePlugin();
})();
