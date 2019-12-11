<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns="http://www.w3.org/1999/xhtml" xmlns:fo="http://www.w3.org/1999/XSL/Format"
		xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
		xmlns:xref="http://www.ecb.int/vocabulary/2002-08-01/eurofxref"
		xmlns:obj="urn:ru:ilb:meta:TestApp:Document"
		xmlns:req="urn:ru:ilb:meta:TestApp:DocumentListRequest"
        xmlns:res="urn:ru:ilb:meta:TestApp:DocumentListResponse"
		exclude-result-prefixes="xsl fo req res obj">
	<xsl:output method="xml" encoding="UTF-8" indent="yes"/>
	<xsl:template match="res:DocumentListResponse">
        <xsl:variable name="req" select="req:DocumentListRequest"/>
		<fo:root xmlns:fo="http://www.w3.org/1999/XSL/Format" font-family="Liberation Serif" font-size="10pt" language="en">
			<fo:layout-master-set>
				<fo:simple-page-master master-name="A4Form" page-height="29.7cm" page-width="21cm" margin="1cm 1.5cm 1cm 1.5cm">
					<fo:region-body />
				</fo:simple-page-master>
			</fo:layout-master-set>
			<fo:page-sequence master-reference="A4Form" initial-page-number="1">
				<fo:flow flow-name="xsl-region-body" font-size="8pt" font-family="Liberation Serif">
					<fo:block-container width="21cm" border-bottom-style="solid" border-width="thin" space-after="2mm">
						<fo:block space-after="2mm">
							<xsl:text>Документы с </xsl:text>
							<xsl:value-of select="$req/req:dateStart"/>
							<xsl:text> по </xsl:text>
							<xsl:value-of select="$req/req:dateEnd"/>
						</fo:block>
						<fo:table table-layout="fixed" width="180mm">
							<fo:table-column column-width="25mm"/>
							<fo:table-column column-width="100mm"/>
							<fo:table-column column-width="25mm"/>
							<fo:table-column column-width="25mm"/>
							<fo:table-body>
								<fo:table-row text-align="center" font-weight="bold" border-top-style="solid" border-bottom-style="solid" border-width="thin">
									<fo:table-cell text-align="left">
										<fo:block>Дата</fo:block>
									</fo:table-cell>
									<fo:table-cell>
										<fo:block>Наименование</fo:block>
									</fo:table-cell>
									<fo:table-cell>
										<fo:block>Ключевые слова</fo:block>
									</fo:table-cell>
									<fo:table-cell text-align="right">
										<fo:block>Удален</fo:block>
									</fo:table-cell>
								</fo:table-row>
								<xsl:for-each select="obj:Document">
									<fo:table-row text-align="center">
										<fo:table-cell text-align="left">
											<fo:block>
												<xsl:value-of select="obj:docDate"/>
											</fo:block>
										</fo:table-cell>
										<fo:table-cell text-align="left">
											<fo:block>
												<xsl:value-of select="obj:displayName"/>
											</fo:block>
										</fo:table-cell>
										<fo:table-cell>
											<fo:block>
												<xsl:value-of select="obj:keywords"/>
											</fo:block>
										</fo:table-cell>
										<fo:table-cell text-align="right">
											<fo:block>
												<xsl:value-of select="obj:deleted"/>
											</fo:block>
										</fo:table-cell>
									</fo:table-row>
								</xsl:for-each>
							</fo:table-body>
						</fo:table>
					</fo:block-container>
				</fo:flow>
			</fo:page-sequence>
		</fo:root>
	</xsl:template>
</xsl:stylesheet>
