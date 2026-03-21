<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Brand;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

class AppController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectUserByRole();
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && $user->role === 'seller' && $user->status == 1) {
            return back()->withErrors([
                'email' => 'YOUR ACCOUNT IS INACTIVE. PLEASE CONTACT ADMIN.',
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return $this->redirectUserByRole();
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    protected function redirectUserByRole()
    {
        $role = Auth::user()->role;

        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($role === 'seller') {
            return redirect()->route('seller.dashboard');
        }

        return redirect('/login');
    }

    public function adminDashboard()
    {
        try {
            $sellerCount = User::where('role', 'seller')->count();
            $brandCount = Brand::join('tbl_product_details', 'tbl_brand_details.product_id', '=', 'tbl_product_details.id')
                ->where('tbl_product_details.delete_status', '0')
                ->count();
            $inventoryCount = Product::where('delete_status', '0')->count();

            return view('admin.admin_dashboard', compact('sellerCount', 'brandCount', 'inventoryCount'));
        } catch (\Exception $e) {
            \Log::error("Dashboard Error: " . $e->getMessage());
            return back()->with('error', 'Unable to load dashboard data. Please try again later.');
        }
    }

    public function sellerDashboard()
    {
        return view('seller.seller_dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function manageSellers()
    {
        try {
            $sellers = User::where('role', 'seller')->get();
            return view('admin.manage_sellers', compact('sellers'));
        } catch (\Exception $e) {
            \Log::error("Manage Sellers Error: " . $e->getMessage());
            return back()->with('error', 'Failed to load sellers list.');
        }
    }

    public function sellerStore(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'mobile_no' => 'required|digits:10',
            'email'     => 'required|email|unique:tbl_admin_details,email',
            'country'   => 'required|string',
            'state'     => 'required|string',
            'password'  => 'required|min:6|confirmed',
        ]);

        try {
            $skills = json_decode($request->skills, true);
            $result = User::create([
                'name'       => $request->full_name,
                'email'      => $request->email,
                'mobile_no'  => $request->mobile_no, 
                'country'    => $request->country,
                'state'      => $request->state,
                'skills'     => json_encode($skills), 
                'password'   => Hash::make($request->password),
                'role'       => 'seller', 
            ]);

            if ($result) {
                return response()->json([
                    'status' => 'success', 
                    'message' => 'SELLER ACCOUNT CREATED IN VAULT!'
                ]);
            }
        } catch (\Exception $e) {
            \Log::error("SELLER_AUTH_ERROR: " . $e->getMessage());
        }

        return response()->json([
            'status' => 'error', 
            'message' => 'DATABASE AUTHORIZATION FAILED!'
        ], 500);
    }

    public function getSellersData()
    {
        try {
            $data = User::where('role', 'seller')
                ->select('id', 'name', 'email', 'mobile_no', 'country', 'state', 'skills', 'status')
                ->get();
            return response()->json(['data' => $data]);
        } catch (\Exception $e) {
            \Log::error("Get Sellers Data Error: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to fetch sellers data. HTTP 500'], 500);
        }
    }

    public function toggleSellerStatus(Request $request)
    {
        try {
            $updated = DB::table('tbl_admin_details')
                ->where('id', $request->id)
                ->update(['status' => $request->status]);

            return response()->json(['status' => 'success', 'message' => 'AUTHORIZED: Status Updated']);
        } catch (\Exception $e) {
            \Log::error("Toggle Seller Status Error: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to update status. Server error.'], 500);
        }
    }

    public function getDashboardStats()
    {
        try {
            $sellerCount = User::where('role', 'seller')->count();
            $brandCount = Brand::join('tbl_product_details', 'tbl_brand_details.product_id', '=', 'tbl_product_details.id')
                ->where('tbl_product_details.delete_status', '0')
                ->count();
            $inventoryCount = Product::where('delete_status', '0')->count();

            return view('admin.admin_dashboard', compact('sellerCount', 'brandCount', 'inventoryCount'));
        } catch (\Exception $e) {
            \Log::error("Dashboard Stats Error: " . $e->getMessage());
            return back()->with('error', 'Unable to fetch statistics at this moment.');
        }
    }

    public function addProduct()
    {
        return view('seller.add_product');
    }

    public function store(Request $request, Product $productModel)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'product_description' => 'nullable|string',
            'brand_name' => 'required|array',
            'brand_name.*' => 'required|string',
            'price.*' => 'required|numeric',
            'detail.*' => 'nullable|string',
            'brand_image.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            DB::beginTransaction();
            $product = Product::create([
                'user_id' => Auth::id(),
                'product_name' => $validated['product_name'],
                'product_description' => $validated['product_description'] ?? null,
            ]);

            if (isset($validated['brand_name'])) {
                foreach ($validated['brand_name'] as $key => $name) {
                    $imagePath = null;
                    if (isset($request->file('brand_image')[$key])) {
                        $file = $request->file('brand_image')[$key];
                        $fileName = time() . "_brand_" . $key . "." . $file->getClientOriginalExtension();
                        $file->move(public_path('brand_images'), $fileName);
                        $imagePath = 'brand_images/' . $fileName;
                    }

                    $product->brands()->create([
                        'brand_name' => $name,
                        'price'      => $validated['price'][$key],
                        'detail'     => $validated['detail'][$key] ?? null,
                        'brand_image'=> $imagePath,
                    ]);
                }
            }
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Saved successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function productList()
    {
        try {
            $products = Product::leftJoin('tbl_brand_details', 'tbl_product_details.id', '=', 'tbl_brand_details.product_id')
                ->where('tbl_product_details.user_id', Auth::id())
                ->where('tbl_product_details.delete_status', '0') 
                ->select([
                    'tbl_product_details.id', 
                    'tbl_product_details.product_name', 
                    'tbl_product_details.product_description', 
                    'tbl_product_details.created_at',
                    DB::raw('MAX(tbl_brand_details.brand_image) as product_image'),
                    DB::raw('COUNT(tbl_brand_details.id) as brands_count')
                ])
                ->groupBy(
                    'tbl_product_details.id', 
                    'tbl_product_details.product_name', 
                    'tbl_product_details.product_description', 
                    'tbl_product_details.created_at'
                )
                ->orderBy('tbl_product_details.id', 'desc')
                ->get();

            return response()->json(['data' => $products]);
        } catch (\Exception $e) {
            \Log::error("Product List Error: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to retrieve products.'], 500);
        }
    }

    public function getProductBrands($id)
    {
        try {
            $brands = Brand::join('tbl_product_details', 'tbl_brand_details.product_id', '=', 'tbl_product_details.id')
                ->where('tbl_brand_details.product_id', $id)
                ->where('tbl_product_details.user_id', Auth::id()) 
                ->where('tbl_product_details.delete_status', '0') 
                ->select('tbl_brand_details.*')
                ->get();

            return response()->json($brands);
        } catch (\Exception $e) {
            \Log::error("Get Product Brands Error: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to fetch product brands.'], 500);
        }
    }

    public function deleteProduct($id)
    {
        try {
            $updated = Product::where('id', $id)
                ->where('user_id', Auth::id())
                ->update(['delete_status' => '1']);

            return $updated 
                ? response()->json(['message' => 'Deleted']) 
                : response()->json(['status' => 'error', 'message' => 'Unauthorized or Not Found'], 403);
        } catch (\Exception $e) {
            \Log::error("Delete Product Error: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to delete product.'], 500);
        }
    }

    public function generateProductInventoryPdf($id)
    {
        try {
            $product = DB::table('tbl_product_details')->where('id', $id)->first();
            if (!$product) {
                return back()->with('error', 'Product not found.');
            }
            
            $brands = DB::table('tbl_brand_details')->where('product_id', $id)->get();
            $totalPrice = $brands->sum('price');

            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_left' => 15,
                'margin_right' => 15,
                'margin_top' => 15,
                'margin_bottom' => 20, 
            ]);

            $css = '
                body { font-family: "Times New Roman", Times, serif; color: #1e293b; background: #ffffff; }
                .header-container { border-bottom: 3px solid #1e293b; padding-bottom: 10px; margin-bottom: 25px; }
                .brand-title { font-size: 22pt; font-weight: bold; color: #1e293b; text-transform: uppercase; letter-spacing: 2px; }
                .sub-brand { font-size: 9pt; color: #b45309; font-weight: bold; letter-spacing: 3px; }
                .delight-card { 
                    border: 1.5pt dashed #cbd5e1; 
                    padding: 15px; 
                    margin-bottom: 30px;
                    width: 100%;
                }
                .meta-label { font-size: 8pt; color: #64748b; text-transform: uppercase; font-weight: bold; letter-spacing: 0.5px; }
                .meta-value { font-size: 11pt; font-weight: bold; color: #1e293b; margin-top: 3px; }
                .product-profile { background: #f8fafc; padding: 20px; border-left: 5px solid #1e293b; margin-bottom: 30px; }
                .section-label { font-size: 7pt; color: #b45309; text-transform: uppercase; font-weight: bold; margin-bottom: 5px; letter-spacing: 1px; }
                .product-h1 { font-size: 18pt; color: #1e293b; margin: 0; font-weight: normal; }
                .product-p { font-size: 10pt; color: #475569; line-height: 1.6; margin-top: 10px; }
                table.inventory-table { width: 100%; border-collapse: collapse; }
                table.inventory-table th { 
                    border-bottom: 2px solid #1e293b; 
                    padding: 12px 8px; 
                    text-align: left; 
                    font-size: 10pt; 
                    color: #1e293b; 
                    text-transform: uppercase;
                    font-weight: bold;
                }
                table.inventory-table td { 
                    padding: 15px 8px; 
                    border-bottom: 1px solid #e2e8f0; 
                    font-size: 10pt; 
                    vertical-align: middle;
                }
                .img-container { border: 1px solid #cbd5e1; padding: 2px; background: #fff; width: 45px; }
                .summary-wrapper { margin-top: 30px; border-top: 2px solid #1e293b; padding-top: 15px; }
                .total-text { font-size: 10pt; color: #64748b; text-transform: uppercase; }
                .total-price { font-size: 18pt; font-weight: bold; color: #1e293b; }
            ';

            $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);

            $footerHTML = '
                <div style="border-top: 1px solid #e2e8f0; padding-top: 10px; font-size: 8pt; color: #94a3b8; width: 100%;">
                    <table width="100%">
                        <tr>
                            <td width="50%">Generated On: ' . date('d M Y, h:i A') . '</td>
                            <td width="50%" align="right">Page {PAGENO} of {nbpg}</td>
                        </tr>
                    </table>
                </div>';
            $mpdf->SetHTMLFooter($footerHTML);

            $html = '
            <div class="header-container">
                <div class="brand-title">PRO STOCK MANAGER</div>
                <div class="sub-brand">PRODUCT PORTFOLIO REPORT</div>
            </div>
            <div class="delight-card">
                <table width="100%">
                    <tr>
                        <td width="50%">
                            <div class="meta-label">Authorised Seller</div>
                            <div class="meta-value">' . strtoupper(Auth::user()->name ?? 'N/A') . '</div>
                        </td>
                        <td width="50%" style="text-align:right;">
                            <div class="meta-label">Reference ID</div>
                            <div class="meta-value">PSM-REF-' . str_pad($product->id, 5, '0', STR_PAD_LEFT) . '</div>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="product-profile">
                <div class="section-label">Product Name & Description</div>
                <div class="product-h1">' . $product->product_name . '</div>
                <div class="product-p">' . $product->product_description . '</div>
            </div>
            <div class="section-label" style="margin-left:8px; margin-bottom:10px;">Brand List</div>
            <table class="inventory-table">
                <thead>
                    <tr>
                        <th width="8%">SR. NO.</th>
                        <th width="15%">BRAND IMAGE</th>
                        <th width="25%">BRAND NAME</th>
                        <th width="32%">PRODUCT DETAIL</th>
                        <th width="20%" style="text-align: right;">PRICE (INR)</th>
                    </tr>
                </thead>
                <tbody>';

            foreach ($brands as $index => $brand) {
                $imgPath = base_path($brand->brand_image);
                $imgSrc = "";

                if (!empty($brand->brand_image) && file_exists($imgPath)) {
                    try {
                        $type = pathinfo($imgPath, PATHINFO_EXTENSION);
                        $data = file_get_contents($imgPath);
                        $imgSrc = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    } catch (\Exception $e) {
                        $imgSrc = "";
                    }
                }

                $html .= '
                    <tr>
                        <td style="color:#94a3b8;">' . ($index + 1) . '</td>
                        <td>';
                if($imgSrc != "") {
                    $html .= '<div class="img-container"><img src="' . $imgSrc . '" style="width:40px; height:40px; display:block;"></div>';
                } else {
                    $html .= '<div style="font-size:7pt; color:#cbd5e1;">NO IMAGE</div>';
                }
                $html .= '</td>
                        <td><div style="font-weight:bold; color:#1e293b;">' . strtoupper($brand->brand_name) . '</div></td>
                        <td style="font-size: 9pt; color: #475569;">' . $brand->detail . '</td>
                        <td style="text-align: right; font-weight: bold;">' . number_format($brand->price, 2) . '</td>
                    </tr>';
            }

            $html .= '
                </tbody>
            </table>
            <table width="100%" class="summary-wrapper">
                <tr>
                    <td width="60%" style="font-size: 8pt; color: #94a3b8; vertical-align: top;">
                        * This document is a system generated report of available stock brands.
                    </td>
                    <td width="40%" style="text-align: right;">
                        <div class="total-text">Total Price</div>
                        <div class="total-price">₹' . number_format($totalPrice, 2) . '</div>
                    </td>
                </tr>
            </table>';

            $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);

            return $mpdf->Output('ProStock_Portfolio_Report.pdf', 'I');
        } catch (\Exception $e) {
            \Log::error("PDF Generation Error: " . $e->getMessage());
            return back()->with('error', 'Unable to generate PDF report at this time. Please try again later.');
        }
    }

    public function getSellerDashboardStats()
    {
        try {
            $sellerId = Auth::id();

            $productCount = Product::where('user_id', $sellerId)
                                ->where('delete_status', '0')
                                ->count();

            $brandCount = Brand::join('tbl_product_details', 'tbl_brand_details.product_id', '=', 'tbl_product_details.id')
                            ->where('tbl_product_details.user_id', $sellerId)
                            ->where('tbl_product_details.delete_status', '0')
                            ->count();

            return view('seller.seller_dashboard', compact('productCount', 'brandCount'));
        } catch (\Exception $e) {
            \Log::error("Seller Dashboard Stats Error: " . $e->getMessage());
            return back()->with('error', 'Unable to fetch dashboard statistics.');
        }
    }

    public function generateDummyProductInventoryPdf()
{
    // Actual Product Data (Professional/Corporate Style)
    $product = (object)[
        'id' => 1024,
        'product_name' => 'ENTERPRISE GRAPHICS ACCELERATORS',
        'product_description' => 'Professional-grade GPU units designed for AI training, 3D rendering, and data science workflows. Features include ECC memory, high-bandwidth interconnects, and specialized cooling for 24/7 data center operation.'
    ];

    $brands = [];
    $brandNames = ['Nvidia RTX A6000', 'AMD Radeon Pro W6800', 'Quadro P-Series', 'Tesla V100 Plus', 'Intel Arc Pro'];
    
    for ($i = 0; $i < 5; $i++) {
        $brands[] = (object)[
            'brand_name' => $brandNames[$i],
            'detail' => 'Bulk Unit - Batch ' . (202601 + $i) . ' | Warranty: 3 Years On-site.',
            'price' => 45000.00 + ($i * 2500),
            'brand_image' => 'brand_images/placeholder.png' 
        ];
    }
    
    $totalPrice = collect($brands)->sum('price');

    $mpdf = new \Mpdf\Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4',
        'margin_left' => 15, 'margin_right' => 15, 'margin_top' => 15, 'margin_bottom' => 20, 
    ]);

    // Same CSS logic for professional styling
    $css = '
        body { font-family: "Times New Roman", Times, serif; color: #1e293b; background: #ffffff; }
        .header-container { border-bottom: 3px solid #1e293b; padding-bottom: 10px; margin-bottom: 25px; }
        .brand-title { font-size: 22pt; font-weight: bold; color: #1e293b; text-transform: uppercase; letter-spacing: 2px; }
        .sub-brand { font-size: 9pt; color: #b45309; font-weight: bold; letter-spacing: 3px; }
        .delight-card { border: 1.5pt dashed #cbd5e1; padding: 15px; margin-bottom: 30px; width: 100%; }
        .meta-label { font-size: 8pt; color: #64748b; text-transform: uppercase; font-weight: bold; }
        .meta-value { font-size: 11pt; font-weight: bold; color: #1e293b; margin-top: 3px; }
        .product-profile { background: #f8fafc; padding: 20px; border-left: 5px solid #1e293b; margin-bottom: 30px; }
        .section-label { font-size: 7pt; color: #b45309; text-transform: uppercase; font-weight: bold; margin-bottom: 5px; }
        .product-h1 { font-size: 18pt; color: #1e293b; margin: 0; }
        .product-p { font-size: 10pt; color: #475569; line-height: 1.6; }
        table.inventory-table { width: 100%; border-collapse: collapse; }
        table.inventory-table th { border-bottom: 2px solid #1e293b; padding: 12px 8px; text-align: left; font-size: 10pt; color: #1e293b; text-transform: uppercase; }
        table.inventory-table td { padding: 15px 8px; border-bottom: 1px solid #e2e8f0; font-size: 10pt; vertical-align: middle; }
        .img-container { border: 1px solid #cbd5e1; padding: 2px; background: #fff; width: 45px; }
        .total-price { font-size: 18pt; font-weight: bold; color: #1e293b; }
    ';

    $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);

    $footerHTML = '
        <div style="border-top: 1px solid #e2e8f0; padding-top: 10px; font-size: 8pt; color: #94a3b8; width: 100%;">
            <table width="100%">
                <tr>
                    <td>Generated: ' . date('d M Y, h:i A') . '</td>
                    <td align="right">Inventory Record: Page {PAGENO} of {nbpg}</td>
                </tr>
            </table>
        </div>';
    $mpdf->SetHTMLFooter($footerHTML);

    $html = '
    <div class="header-container">
        <div class="brand-title">PRO STOCK MANAGER</div>
        <div class="sub-brand">CERTIFIED INVENTORY STATEMENT</div>
    </div>
    <div class="delight-card">
        <table width="100%">
            <tr>
                <td width="50%">
                    <div class="meta-label">Certified Logged By</div>
                    <div class="meta-value">' . strtoupper(Auth::user()->name ?? 'NIKHIL KOTHE') . '</div>
                </td>
                <td width="50%" style="text-align:right;">
                    <div class="meta-label">Reference ID</div>
                    <div class="meta-value">PSM-REF-' . str_pad($product->id, 6, '0', STR_PAD_LEFT) . '</div>
                </td>
            </tr>
        </table>
    </div>
    <div class="product-profile">
        <div class="section-label">Master Product Category</div>
        <div class="product-h1">' . $product->product_name . '</div>
        <div class="product-p">' . $product->product_description . '</div>
    </div>
    <table class="inventory-table">
        <thead>
            <tr>
                <th width="8%">SR.</th>
                <th width="15%">ASSET</th>
                <th width="25%">BRAND</th>
                <th width="32%">SPECIFICATIONS</th>
                <th width="20%" style="text-align: right;">VALUATION (INR)</th>
            </tr>
        </thead>
        <tbody>';

    foreach ($brands as $index => $brand) {
        $imgPath = base_path($brand->brand_image);
        $imgSrc = "";
        if (file_exists($imgPath)) {
            $type = pathinfo($imgPath, PATHINFO_EXTENSION);
            $data = file_get_contents($imgPath);
            $imgSrc = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        $html .= '
            <tr>
                <td style="color:#94a3b8;">' . ($index + 1) . '</td>
                <td>';
        if($imgSrc != "") {
            $html .= '<div class="img-container"><img src="' . $imgSrc . '" style="width:40px; height:40px;"></div>';
        }
        $html .= '</td>
                <td><div style="font-weight:bold;">' . strtoupper($brand->brand_name) . '</div></td>
                <td>' . $brand->detail . '</td>
                <td style="text-align: right; font-weight: bold;">' . number_format($brand->price, 2) . '</td>
            </tr>';
    }

    $html .= '
        </tbody>
    </table>
    <div style="text-align: right; margin-top: 30px;">
        <div style="font-size: 10pt; color: #64748b;">TOTAL CONSOLIDATED VALUE</div>
        <div class="total-price">₹' . number_format($totalPrice, 2) . '</div>
    </div>';

    $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);

    return $mpdf->Output('ProStock_Inventory_Report.pdf', 'I');
}
}