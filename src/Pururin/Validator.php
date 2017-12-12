<?php

namespace Pururin;

class Validator
{
	public function __construct($url, $totalPage, $path)
	{
		$this->url = $url;
		$this->totalPage = $totalPage;
		$i = 1;
		while (file_exists($path."/".$i.".jpg")) {
			$this->offset = $i++;
		}
		if ($totalPage > $i) {
			$this->status = "pending";
		} else {
			$this->status = "success";
		}
	}

	public function run()
	{
		if ($this->status === "pending") {
			print "Redownload... $this->url\n";
			try {
				$app = new PururinCrawler(
					[
						"save_directory" => PURURIN_DATA,
						"manga_url"		 => $this->url,
						"offset"		 => $this->offset
					]
				);
				if ($app->run()) {
					$st = DB::prepare(
						"INSERT INTO `pururin_main_data` (`id`, `title`, `info`, `origin_link`, `created_at`, `updated_at`) VALUES (:id, :title, :info, :origin_link, :created_at, :updated_at);"
					);
					$st->execute(
						array_merge(
							$data = $app->getResult(), 
							[
								"created_at" => date("Y-m-d H:i:s"), 
								"updated_at" => null
							]
						)
					);
					$data['info'] = json_decode($data['info'], true);
					if (isset($data['info']['Contents'])) {
						$query = "INSERT INTO `pururin_genres` (`id`,`genre`) VALUES ";
						$i = 0;
						$queryValue = [
							":id" => $data['id']
						];
						foreach ($data['info']['Contents'] as $val) {
							$query .= "(:id, :genre{$i}),";
							$queryValue[':genre'.($i++)] = $val;
						}
						$st = DB::prepare(rtrim($query, ","));
						$exe = $st->execute($queryValue);
					}
				}
				print "Success... $this->url\n";
				return true;
			} catch (\Exception $e) {
				print "Failed... $this->url\n";
				return false;
			}
		} else {
			return "Valid";
		}
	}
}