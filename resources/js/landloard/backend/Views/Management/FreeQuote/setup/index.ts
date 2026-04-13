import app_config from "@config/app_config";
import setup_type from "@/shared/setup/setup_type";

const prefix: string = "Free Quote";

const setup: setup_type = {
  prefix,
  permission: ["admin", "super_admin"],

  api_host: app_config.api_host,
  api_version: app_config.api_version,
  api_end_point: "free-quotes",

  module_name: "free_quote",
  store_prefix: "free_quote",
  route_prefix: "FreeQuote",
  route_path: "free_quotes",

  select_fields: [
    "id",
    "full_name",
    "email",
    "phone",
    "company_name",
    "service_required",
    "budget_range",
    "project_description",
    "slug",
    "status",
    "created_at",
    "updated_at",
    "deleted_at",
  ],

  sort_by_cols: [
    "id",
    "full_name",
    "email",
    "phone",
    "company_name",
    "service_required",
    "status",
    "created_at",
  ],
  table_header_data: [
    "id",
    "full_name",
    "email",
    "phone",
    "company_name",
    "service_required",
    "status",
    "created_at",
  ],
  table_row_data: [
    "id",
    "full_name",
    "email",
    "phone",
    "company_name",
    "service_required",
    "status",
    "created_at",
  ],

  layout_title: prefix + " Management",
  page_title: `${prefix} Management`,

  all_page_title: "All " + prefix + "s",
  details_page_title: "Details " + prefix,
  create_page_title: "Create " + prefix,
  edit_page_title: "Edit " + prefix,
};

export default setup;
