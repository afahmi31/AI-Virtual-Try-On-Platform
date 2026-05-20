import { chromium } from "playwright";

const baseUrl = process.env.UAT_BASE_URL || "http://127.0.0.1:8000";
const runId = Date.now();
const sellerSlug = `uat-seller-${runId}`;
const sellerStoreName = `UAT Seller ${runId}`;
const sellerOwnerName = `UAT Owner ${runId}`;
const sellerOwnerPassword = "password";
const sellerOwnerEmail = `${sellerSlug}@seller.local`;
const productName = `UAT Product ${runId}`;
const productImageUrl = "https://picsum.photos/seed/uat-tryon/800/800";

const steps = [];

function pass(step) {
  steps.push({ step, status: "PASS" });
}

function fail(step, error) {
  steps.push({ step, status: "FAIL", error: error?.message || String(error) });
  throw error;
}

async function login(page, email, password) {
  await page.goto(`${baseUrl}/login`, { waitUntil: "domcontentloaded" });
  await page.fill('input[name="email"]', email);
  await page.fill('input[name="password"]', password);
  await page.click('button[type="submit"]');
}

const browser = await chromium.launch({ headless: true });
const context = await browser.newContext();
const page = await context.newPage();

try {
  try {
    await login(page, "admin@tryon.test", "password");
    await page.waitForURL(/\/admin$/, { timeout: 15000 });
    await page.waitForSelector("text=Admin Dashboard", { timeout: 15000 });
    pass("Login Admin");
  } catch (error) {
    fail("Login Admin", error);
  }

  try {
    await page.goto(`${baseUrl}/admin/sellers`, { waitUntil: "domcontentloaded" });
    await page.click("button:has-text('Create New Seller')");
    await page.fill('#createModal input[name="store_name"]', sellerStoreName);
    await page.fill('#createModal input[name="slug"]', sellerSlug);
    await page.selectOption('#createModal select[name="status"]', "active");
    await page.fill('#createModal input[name="initial_token_balance"]', "20");
    await page.fill('#createModal input[name="owner_name"]', sellerOwnerName);
    await page.fill('#createModal input[name="owner_password"]', sellerOwnerPassword);
    await page.click('#createModal button:has-text("Create Seller")');
    await page.waitForURL(/\/admin\/sellers/, { timeout: 15000 });
    await page.waitForSelector(`text=${sellerStoreName}`, { timeout: 15000 });
    pass("Admin Create Seller");
  } catch (error) {
    fail("Admin Create Seller", error);
  }

  try {
    const row = page.locator("tr", { hasText: sellerStoreName }).first();
    await row.locator("button:has-text('Top up')").click();
    await page.fill('#topupModal input[name="amount"]', "5");
    await page.click('#topupModal button:has-text("Submit Top up")');
    await page.waitForURL(/\/admin\/sellers/, { timeout: 15000 });
    await page.waitForSelector(`text=${sellerStoreName}`, { timeout: 15000 });
    pass("Admin Topup Seller");
  } catch (error) {
    fail("Admin Topup Seller", error);
  }

  try {
    const row = page.locator("tr", { hasText: sellerStoreName }).first();
    const sellerPublicLink = row.locator(`a[href="/${sellerSlug}"]`).first();
    await sellerPublicLink.waitFor({ timeout: 10000 });
    pass("Admin Verify Seller Listed");
  } catch (error) {
    fail("Admin Verify Seller Listed", error);
  }

  try {
    await page.click('button:has-text("Logout")');
    await page.waitForURL(/\/login$/, { timeout: 15000 });
    pass("Logout Admin");
  } catch (error) {
    fail("Logout Admin", error);
  }

  try {
    await login(page, sellerOwnerEmail, sellerOwnerPassword);
    await page.waitForURL(/\/dashboard$/, { timeout: 15000 });
    await page.waitForSelector("text=Seller Dashboard", { timeout: 15000 });
    pass("Login New Seller");
  } catch (error) {
    fail("Login New Seller", error);
  }

  try {
    await page.goto(`${baseUrl}/dashboard/products`, { waitUntil: "domcontentloaded" });
    await page.click('button:has-text("Add New Product")');
    await page.fill('#createModal input[name="name"]', productName);
    await page.fill('#createModal input[name="sku"]', `SKU-${runId}`);
    await page.fill('#createModal input[name="category"]', "UAT Category");
    await page.fill('#createModal input[name="image_url"]', productImageUrl);
    await page.evaluate(() => {
      const modal = document.querySelector("#createModal");
      const form = modal?.querySelector("form");
      if (!form) {
        throw new Error("Create product form not found");
      }
      form.requestSubmit();
    });
    await page.waitForURL(/\/dashboard\/products/, { timeout: 15000 });
    await page.waitForSelector(`text=${productName}`, { timeout: 15000 });
    pass("Seller Create Product");
  } catch (error) {
    fail("Seller Create Product", error);
  }

  try {
    await page.click('button:has-text("Logout")');
    await page.waitForURL(/\/login$/, { timeout: 15000 });
    pass("Logout Seller");
  } catch (error) {
    fail("Logout Seller", error);
  }

  try {
    await page.goto(`${baseUrl}/${sellerSlug}`, { waitUntil: "domcontentloaded" });
    await page.waitForSelector(`text=${sellerStoreName}`, { timeout: 15000 });
    await page.waitForSelector(`text=${productName}`, { timeout: 15000 });
    await page.waitForSelector("text=Try-On Tool", { timeout: 15000 });
    pass("Public Store Can Access Product Catalog");
  } catch (error) {
    fail("Public Store Can Access Product Catalog", error);
  }
} finally {
  await context.close();
  await browser.close();
}

const failed = steps.filter((step) => step.status === "FAIL");
const summary = {
  baseUrl,
  runId,
  total: steps.length,
  passed: steps.length - failed.length,
  failed: failed.length,
  steps,
};

console.log(JSON.stringify(summary, null, 2));
if (failed.length > 0) {
  process.exit(1);
}
